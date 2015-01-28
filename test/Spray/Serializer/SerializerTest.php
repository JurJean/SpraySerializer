<?php

namespace Spray\Serializer;

use DateTime;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\TestAssets\InheritedSubject;
use Spray\Serializer\TestAssets\Subject;

class SerializerTest extends PHPUnit_Framework_TestCase
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
    
    public function testSerializeInheritedSubject()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $subject = new InheritedSubject(
            'foo',
            'bar',
            'baz',
            new Subject(
                'foo',
                'bar',
                'baz'
            ),
            $date
        );
        $this->assertEquals(
            array(
                'foo' => 'foo',
                'bar' => 'bar',
                'baz' => 'baz',
                'foobar' => array(
                    'foo' => 'foo',
                    'bar' => 'bar',
                    'baz' => 'baz',
                ),
                'barbaz' => '2015-01-01 12:00:00'
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testDeserializeInheritedSubject()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
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
        $this->assertEquals(
            new InheritedSubject('foo', 'bar', 'baz', new Subject('foo', 'bar', 'baz'), $date),
            $this->buildSerializer()->deserialize('Spray\Serializer\TestAssets\InheritedSubject', $data)
        );
    }
}
