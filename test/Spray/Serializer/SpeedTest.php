<?php

namespace Spray\Serializer;

use DateTime;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\TestAssets\InheritedSubject;
use Spray\Serializer\TestAssets\Subject;

class SpeedTest extends PHPUnit_Framework_TestCase
{
    protected function buildSerializer()
    {
        $registry = new SerializerRegistry();
        $registry->add(new DateTimeSerializer());
        $builder = new ObjectSerializerBuilder(new ReflectionRegistry());
        $cache = new ArrayCache();
        $locator = new SerializerLocator($registry, $builder, $cache);
        return new Serializer($locator);
    }
    
    public function testSerializationSpeed()
    {
        $date = new DateTime();
        $serializer = $this->buildSerializer();
        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $serializer->serialize(new InheritedSubject(
                'foo', 'bar', 'baz',
                new Subject('foo', 'bar', 'baz'),
                $date
            ));
        }
        $this->assertLessThan(1, microtime(true) - $time);
    }
    
    public function testDeserializationSpeed()
    {
        $date = new DateTime();
        $data = array(
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
            'foobar' => array(
                'foo' => 'foo',
                'bar' => 'bar',
                'baz' => 'baz',
            ),
            'barbaz' => '2015-01-01 12:00:00'
        );
        $serializer = $this->buildSerializer();
        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $serializer->deserialize('Spray\Serializer\TestAssets\InheritedSubject', $data);
        }
        $this->assertLessThan(1, microtime(true) - $time);
    }
}
