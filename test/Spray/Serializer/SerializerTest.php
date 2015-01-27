<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\TestAssets\InheritedSubject;

class SerializerTest extends PHPUnit_Framework_TestCase
{
    protected function buildSerializer()
    {
        $registry = new SerializerRegistry();
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        $cache = new ArrayCache();
        $locator = new SerializerLocator($registry, $builder, $cache);
        return new Serializer($locator);
    }
    
    public function testSerializeInheritedSubject()
    {
        $subject = new InheritedSubject('foo', 'bar', 'baz', 'foobar');
        $this->assertEquals(
            array(
                'foo' => 'foo',
                'bar' => 'bar',
                'baz' => 'baz',
                'foobar' => 'foobar',
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testDeserializeInheritedSubject()
    {
        $data = array(
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
            'foobar' => 'foobar',
        );
        $this->assertEquals(
            new InheritedSubject('foo', 'bar', 'baz', 'foobar'),
            $this->buildSerializer()->deserialize('Spray\Serializer\TestAssets\InheritedSubject', $data)
        );
    }
}
