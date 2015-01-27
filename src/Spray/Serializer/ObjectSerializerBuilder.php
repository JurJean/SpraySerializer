<?php

namespace Spray\Serializer;

use RuntimeException;
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
    
    protected function isTargetObject(PropertyReflection $property)
    {
        if ( ! $property->getDocBlock()) {
            return false;
        }
        if ( ! $property->getDocBlock()->hasTag('var')) {
            return false;
        }
        
        $scalar = array(
            'int',
            'float',
            'double',
            'string',
            'array'
        );
        
        if (in_array($property->getDocBlock()->getTag('var')->getContent(), $scalar)) {
            return false;
        }
        if (class_exists($property->getDocBlock()->getTag('var')->getContent())) {
            return true;
        }
        if (class_exists(sprintf(
            '%s\\%s',
            $property->getDeclaringClass()->getNamespaceName(),
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
        
        if (class_exists($property->getDocBlock()->getTag('var')->getContent())) {
            return $property->getDocBlock()->getTag('var')->getContent();
        }
        
        return sprintf(
            '%s\\%s',
            $property->getDeclaringClass()->getNamespaceName(),
            $property->getDocBlock()->getTag('var')->getContent()
        );
    }
}
