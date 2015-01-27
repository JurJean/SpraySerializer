<?php

namespace Spray\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit_Framework_TestCase;

class ObjectSerializerBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testBuildSerializer()
    {
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/ExpectedInheritedSubjectSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\InheritedSubject')
        );
    }
}
