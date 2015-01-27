<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class ObjectSerializerBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ReflectionRegistryInterface
     */
    private $reflections;
    
    protected function setUp()
    {
       $this->reflections = $this->getMock('Spray\Serializer\ReflectionRegistryInterface'); 
    }
    
    public function testBuildSerializer()
    {
        $this->reflections->expects($this->at(0))
            ->method('getReflection')
            ->with($this->equalTo('Spray\Serializer\TestAssets\InheritedSubject'))
            ->will($this->returnValue(new ReflectionClass('Spray\Serializer\TestAssets\InheritedSubject')));
        $this->reflections->expects($this->at(1))
            ->method('getReflection')
            ->with($this->equalTo('Spray\Serializer\TestAssets\Subject'))
            ->will($this->returnValue(new ReflectionClass('Spray\Serializer\TestAssets\Subject')));
        $builder = new ObjectSerializerBuilder($this->reflections);
        
        $this->assertEquals(
            file_get_contents(__DIR__ . '/TestAssets/ExpectedInheritedSubjectSerializer.php'),
            $builder->build('Spray\Serializer\TestAssets\InheritedSubject')
        );
    }
}
