<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\TestAssets\Bar;
use Spray\Serializer\TestAssets\BarCollection;
use Spray\Serializer\TestAssets\WithHashMap;
use Zend\Code\Reflection\PropertyReflection;

class AnnotationBackedPropertyInfoTest extends PHPUnit_Framework_TestCase
{
    public function testFindTargetArrayClass()
    {
        $property = new PropertyReflection(WithHashMap::class, 'objectsArray');
        $this->assertEquals(Bar::class, $this->createInfo()->findTargetArrayClass($property));

        $property = new PropertyReflection(BarCollection::class, 'items');
        $this->assertEquals(Bar::class, $this->createInfo()->findTargetArrayClass($property));
    }

    public function testIsTargetArray()
    {
        $property = new PropertyReflection(WithHashMap::class, 'stringsArray');
        $this->assertTrue($this->createInfo()->isTargetArray($property));

        $property = new PropertyReflection(WithHashMap::class, 'objectsArray');
        $this->assertTrue($this->createInfo()->isTargetArray($property));

        $property = new PropertyReflection(BarCollection::class, 'items');
        $this->assertTrue($this->createInfo()->isTargetArray($property));
    }

    public function testIsTargetArrayWithObjects()
    {
        $property = new PropertyReflection(WithHashMap::class, 'objectsArray');
        $this->assertTrue($this->createInfo()->isTargetArrayWithObjects($property));

        $property = new PropertyReflection(BarCollection::class, 'items');
        $this->assertTrue($this->createInfo()->isTargetArrayWithObjects($property));
    }

    public function testIsNotATargetArrayWithObjects()
    {
        $property = new PropertyReflection(WithHashMap::class, 'stringsArray');
        $this->assertFalse($this->createInfo()->isTargetArrayWithObjects($property));
    }

    private function createInfo()
    {
        return new AnnotationBackedPropertyInfo();
    }
}
