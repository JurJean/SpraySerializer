<?php

namespace Spray\Serializer;

use RuntimeException;
use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\PropertyReflection;

class AnnotationBackedPropertyInfo implements PropertyInfoInterface
{
    public function findPropertiesDefinedInClass(ClassReflection $class)
    {
        $parent = $class->getParentClass();
        if (false === $parent) {
            return $this->findProperties($class);
        }
        return array_diff(
            $this->findProperties($class),
            $this->findProperties($parent)
        );
    }

    protected function findProperties(ClassReflection $class)
    {
        $result = array();
        foreach ($class->getProperties() as $property) {
            $result[] = $property;
        }
        return $result;
    }

    public function hasPropertyAnnotation(PropertyReflection $property, $annotationName)
    {
        if ( ! $property->getDocBlock()) {
            return false;
        }
        if ( ! $property->getDocBlock()->hasTag($annotationName)) {
            return false;
        }
        return true;
    }

    public function isTargetScalar(PropertyReflection $property)
    {
        return $this->isTargetString($property)
            || $this->isTargetInteger($property)
            || $this->isTargetDouble($property)
            || $this->isTargetFloat($property);
    }

    public function isTargetString(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'string',
        ));
    }

    public function isTargetInteger(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'int',
            'integer'
        ));
    }

    public function isTargetDouble(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'double',
        ));
    }

    public function isTargetFloat(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'float',
        ));
    }

    public function isTargetBoolean(PropertyReflection $property)
    {
        return in_array($property->getDocBlock()->getTag('var')->getContent(), array(
            'boolean',
            'bool'
        ));
    }

    public function isTargetArray(PropertyReflection $property)
    {
        return 'array' === substr($property->getDocBlock()->getTag('var')->getContent(), 0, 5);
    }

    public function isTargetArrayWithObjects(PropertyReflection $property)
    {
        return 0 !== preg_match(
            '|^array<(.*)>|',
            $property->getDocBlock()->getTag('var')->getContent()
        );
    }

    public function findTargetArrayClass(PropertyReflection $property)
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

    public function isTargetObject(PropertyReflection $property)
    {
        if (($class = $this->findClass(
            $property->getDeclaringClass(),
            $property->getDocBlock()->getTag('var')->getContent()))) {
            return true;
        }
        return false;
    }

    public function findTargetClass(PropertyReflection $property)
    {
        if ( ! $this->isTargetObject($property)) {
            throw new RuntimeException(sprintf(
                'Target value of property %s::%s is not an object',
                $property->getDeclaringClass()->getName(),
                $property->getName()
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
        foreach ($this->findAliasedImports($reflection) as $alias => $aliasedImport) {
            if ($alias === $class) {
                return $aliasedImport;
            }
        }

        foreach ($this->findImports($reflection) as $import) {
            $importReflection = new ClassReflection($import);
            if ($importReflection->getShortName() === $class) {
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

    protected function findAliasedImports(ClassReflection $reflection)
    {
        $matches = array();

        preg_match_all(
            '/^use ([a-zA-Z0-9\\\\]*) as ([a-zA-Z0-9]*)\;$/im',
            file_get_contents($reflection->getFileName()),
            $matches
        );

        $result = array();
        foreach ($matches[1] as $i => $value) {
            if ( ! isset($matches[2][$i])) {
                continue;
            }
            $result[$matches[2][$i]] = $matches[1][$i];
        }

        return $result;
    }

    protected function findImports(ClassReflection $reflection)
    {
        $matches = array();

        preg_match_all(
            '/^use ([a-zA-Z0-9\\\\]*)\;$/im',
            file_get_contents($reflection->getFileName()),
            $matches
        );

        return $matches[1];
    }
}
