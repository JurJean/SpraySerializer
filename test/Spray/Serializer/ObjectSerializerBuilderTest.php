<?php

namespace Spray\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit_Framework_TestCase;

class ObjectSerializerBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuildSubjectSerializer()
    {
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/ExpectedSubjectSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\Subject')
        );
    }
    public function testBuildInheritedSubjectSerializer()
    {
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/ExpectedInheritedSubjectSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\InheritedSubject')
        );
    }
}
