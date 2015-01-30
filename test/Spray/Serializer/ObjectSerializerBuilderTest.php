<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;

class ObjectSerializerBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuildSubjectSerializer()
    {
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/SubjectSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\Subject')
        );
    }
    
    public function testBuildFoo()
    {
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/FooSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\Foo')
        );
    }
}
