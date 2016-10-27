<?php

namespace Spray\Serializer;

use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\PropertyReflection;

interface PropertyInfoInterface
{
    public function findPropertiesDefinedInClass(ClassReflection $class);

    public function hasPropertyAnnotation(PropertyReflection $property, $annotationName);

    public function propertyType(PropertyReflection $property);

    public function hashMapPropertyType(PropertyReflection $property);

    public function isIgnored(PropertyReflection $propertyReflection);

    public function isTargetScalar(PropertyReflection $property);

    public function isTargetString(PropertyReflection $property);

    public function isTargetInteger(PropertyReflection $property);

    public function isTargetDouble(PropertyReflection $property);

    public function isTargetFloat(PropertyReflection $property);

    public function isTargetBoolean(PropertyReflection $property);

    public function isTargetArray(PropertyReflection $property);

    public function isTargetHashMap(PropertyReflection $property);

    public function isTargetArrayWithObjects(PropertyReflection $property);

    public function findTargetArrayClass(PropertyReflection $property);

    public function isTargetObject(PropertyReflection $property);

    public function findTargetClass(PropertyReflection $property);
}
