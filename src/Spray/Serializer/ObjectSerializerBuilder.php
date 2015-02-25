<?php

namespace Spray\Serializer;

use RuntimeException;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\PropertyReflection;

class ObjectSerializerBuilder implements ObjectSerializerBuilderInterface
{
    /**
     * @var ReflectionRegistryInterface
     */
    private $reflections;
    
    public function __construct(ReflectionRegistryInterface $reflections)
    {
        $this->reflections = $reflections;
    }
    
    public function build($subject)
    {
        $reflection = $this->reflections->getReflection($subject);
        $properties = $this->properties($subject);
        
        ob_start();
        include sprintf(
            '%s/ObjectSerializerBuilder.template',
            __DIR__
        );
        return ob_get_clean();
    }
    
    protected function properties($subject)
    {
        $result = array();
        foreach ($this->findPropertiesDefinedInClass($subject) as $property) {
            $result[] = $this->reflections->getReflection($subject)->getProperty($property);
        }
        return $result;
    }
    
    protected function findPropertiesDefinedInClass($subject)
    {
        $parent = get_parent_class($subject);
        if (false === $parent) {
            return $this->findProperties($subject);
        }
        return array_diff(
            $this->findProperties($subject),
            $this->findProperties($parent)
        );
    }
    
    protected function findProperties($subject)
    {
        $result = array();
        foreach ($this->reflections->getReflection($subject)->getProperties() as $property) {
            $result[] = $property->getName();
        }
        return $result;
    }
    
    protected function hasTargetAnnotation(PropertyReflection $property)
    {
        if ( ! $property->getDocBlock()) {
            return false;
        }
        if ( ! $property->getDocBlock()->hasTag('var')) {
            return false;
        }
        return true;
    }
    
    protected function isTargetScalar(PropertyReflection $property)
    {
        return $this->isTargetString($property)
            || $this->isTargetInteger($property)
            || $this->isTargetDouble($property)
            || $this->isTargetFloat($property);
    }
    
    protected function isTargetString(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'string',
        ));
    }
    
    protected function isTargetInteger(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'int',
            'integer'
        ));
    }
    
    protected function isTargetDouble(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'double',
        ));
    }
    
    protected function isTargetFloat(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'float',
        ));
    }
    
    protected function isTargetBoolean(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'boolean',
            'bool'
        ));
    }
    
    protected function isTargetArray(PropertyReflection $property)
    {
        return 'array' === substr($property->getDocBlock()->getTag('var')->getContent(), 0, 5);
    }
    
    protected function isTargetArrayWithObjects(PropertyReflection $property)
    {
        return 0 !== preg_match(
            '|^array<(.*)>|',
            $property->getDocBlock()->getTag('var')->getContent()
        );
    }
    
    protected function determineTargetArrayClass(PropertyReflection $property)
    {
        $matches = array();
        preg_match(
            '|^array<(.*)>|',
            $property->getDocBlock()->getTag('var')->getContent(),
            $matches
        );
        
        if (($class = $this->findClass($property->getDeclaringClass(), $matches[1]))) {
            return $class;
        }
        
        throw new RuntimeException(sprintf(
            'Cannot find target for array<%s>',
            $matches[1]
        ));
    }
    
    protected function isTargetObject(PropertyReflection $property)
    {
        if (($class = $this->findClass(
            $property->getDeclaringClass(),
            $property->getDocBlock()->getTag('var')->getContent()))) {
            return true;
        }
        return false;
    }
    
    public function getTargetClass($property)
    {
        if ( ! $this->isTargetObject($property)) {
            throw new RuntimeException(sprintf(
                'Target value of property %s is not an object',
                $property
            ));
        }
        return $this->findClass(
            $property->getDeclaringClass(),
            $property->getDocBlock()->getTag('var')->getContent()
        );
    }
    
    protected function findClass(ClassReflection $reflection, $class)
    {
        if (($className = $this->findClassInCurrentNamespace($reflection, $class))) {
            return $className;
        }
        if (($className = $this->findClassInImports($reflection, $class))) {
            return $className;
        }
        if (($className = $this->findClassInRoot($class))) {
            return $className;
        }
    }
    
    protected function findClassInCurrentNamespace(ClassReflection $reflection, $class)
    {
        $fqn = sprintf(
            '%s\\%s',
            $reflection->getNamespaceName(),
            $class
        );
        if (class_exists($fqn) || interface_exists($fqn)) {
            return $fqn;
        }
    }
    
    protected function findClassInImports(ClassReflection $reflection, $class)
    {
        foreach ($this->findImports($reflection) as $import) {
            if ($this->reflections->getReflection($import)->getShortName() === $class) {
                return $import;
            }
        }
    }
    
    protected function findClassInRoot($class)
    {
        if (class_exists($class) || interface_exists($class)) {
            return $class;
        }
    }
    
    protected function findImports(ClassReflection $reflection)
    {
        $matches = array();
        
        preg_match_all(
            '/^use (.*)\;$/im',
            file_get_contents($reflection->getFileName()),
            $matches
        );
        
        return $matches[1];
    }
}
