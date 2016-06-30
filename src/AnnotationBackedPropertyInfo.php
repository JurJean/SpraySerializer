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
        return $this->isTargetBoolean($property)
            || $this->isTargetString($property)
            || $this->isTargetInteger($property)
            || $this->isTargetDouble($property)
            || $this->isTargetFloat($property);
    }

    public function isTargetString(PropertyReflection $property)
    {
        return $this->isStringType($this->propertyType($property));
    }

    public function isTargetInteger(PropertyReflection $property)
    {
        return $this->isIntegerType($this->propertyType($property));
    }

    public function isTargetDouble(PropertyReflection $property)
    {
        return $this->isDoubleType($this->propertyType($property));
    }

    public function isTargetFloat(PropertyReflection $property)
    {
        return $this->isFloatType($this->propertyType($property));
    }

    public function isTargetBoolean(PropertyReflection $property)
    {
        return $this->isBooleanType($this->propertyType($property));
    }

    public function isTargetArray(PropertyReflection $property)
    {
        return ! $this->isTargetHashMap($property)
            && (
                'array' === substr($this->propertyType($property), 0, 5)
                || 1 === preg_match('/^(.*)\[\]/', $this->propertyType($property))
            );
    }

    public function isTargetHashMap(PropertyReflection $property)
    {
        return 1 === preg_match(
            '|^array<(.*),(.*)>|',
            $this->propertyType($property),
            $matches
        );
    }

    public function isTargetArrayWithObjects(PropertyReflection $property)
    {
        $matches = [];
        if (1 === preg_match('/^array<(.*)>/', $this->propertyType($property), $matches)) {
            $parts = explode(',', $matches[1]);
            $shortName = array_pop($parts);

            return ! $this->isScalarType($shortName);
        }
        if (1 === preg_match('/^(.*)\[\]/', $this->propertyType($property), $matches)) {
            return ! $this->isScalarType($matches[1]);
        }

        return false;
    }

    public function findTargetArrayClass(PropertyReflection $property)
    {
        $matches = array();
        if (1 === preg_match('/^array<(.*)>/i', $this->propertyType($property), $matches)) {
            $parts = explode(',', $matches[1]);
            $shortName = array_pop($parts);

            if (($class = $this->findClass($property->getDeclaringClass(), $shortName))) {
                return $class;
            }

            throw new RuntimeException(sprintf(
                'Cannot find target for array<%s>',
                $matches[1]
            ));
        }

        if (1 === preg_match('/^(.*)\[\]/i', $this->propertyType($property), $matches)) {
            if (($class = $this->findClass($property->getDeclaringClass(), $matches[1]))) {
                return $class;
            }

            throw new RuntimeException(sprintf(
                'Cannot find target for %s[]',
                $matches[1]
            ));
        }
    }

    public function isTargetObject(PropertyReflection $property)
    {
        if (($class = $this->findClass(
            $property->getDeclaringClass(),
            $this->propertyType($property)))) {
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
            $this->propertyType($property)
        );
    }

    protected function findClass(ClassReflection $reflection, $class)
    {
        if (($className = $this->findClassByFqn($reflection, $class))) {
            return $className;
        }
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

    protected function findClassByFqn(ClassReflection $reflection, $class)
    {
        if ('\\' !== substr($class, 0, 1)) {
            return null;
        }
        $fqn = ltrim($class, '\\');
        if (class_exists($fqn) || interface_exists($fqn)) {
            return $fqn;
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

    private function isScalarType($type)
    {
        return $this->isBooleanType($type)
            || $this->isStringType($type)
            || $this->isIntegerType($type)
            || $this->isDoubleType($type)
            || $this->isFloatType($type);
    }

    private function isBooleanType($type)
    {
        return in_array($type, array(
            'boolean',
            'bool'
        ));
    }

    private function isStringType($type)
    {
        return in_array($type, array(
            'string',
        ));
    }

    private function isIntegerType($type)
    {
        return in_array($type, array(
            'int',
            'integer'
        ));
    }

    private function isDoubleType($type)
    {
        return in_array($type, array(
            'double',
        ));
    }

    private function isFloatType($type)
    {
        return in_array($type, array(
            'float'
        ));
    }

    public function propertyType(PropertyReflection $property)
    {
        if ( ! $this->hasPropertyAnnotation($property, 'var')) {
            throw new RuntimeException(sprintf(
                'Cannot determine property type for %s::$%s as no @var annotation was defined',
                $property->getDeclaringClass()->getName(),
                $property->getName()
            ));
        }
        return $property->getDocBlock()->getTag('var')->getContent();
    }

    public function hashMapPropertyType(PropertyReflection $property)
    {
        if ( ! $this->isTargetHashMap($property)) {
            throw new RuntimeException(sprintf(
                'Could not determine hash map property type as %s::$%s is not configured as a hash map: %s instead of array<t,t>',
                $property->getDeclaringClass()->getName(),
                $property->getName(),
                $this->propertyType($property)
            ));
        }

        preg_match(
            '|^array<(.*)>|',
            $this->propertyType($property),
            $matches
        );

        $parts = explode(',', $matches[1]);
        return array_pop($parts);
    }
}
