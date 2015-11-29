<?php

namespace Spray\Serializer\Object;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\ReflectionRegistry;

class ObjectSerializerGeneratorTest extends PHPUnit_Framework_TestCase
{
    private function createGenerator()
    {
        return new ObjectSerializerGenerator(new AnnotationBackedPropertyInfo());
    }

    public function testGenerateSubjectSerializer()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/SubjectSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\Subject')
        );
    }
    
    public function testGenerateFoo()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/FooSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\Foo')
        );
    }
    
    public function testGenerateBar()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/BarSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\Bar')
        );
    }
    
    public function testGenerateBarCollection()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/BarCollectionSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\BarCollection')
        );
    }
    
    public function testGenerateHasInterfaceRelation()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/HasInterfaceRelationSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\HasInterfaceRelation')
        );
    }
    
    public function testGenerateScalar()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/ScalarSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\Scalar')
        );
    }
    
    public function testGenerateWithOtherNamespace()
    {
        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/WithOtherNamespaceSerializer.php'),
            $this->createGenerator()->generate('Spray\Serializer\TestAssets\WithOtherNamespace')
        );
    }
}
