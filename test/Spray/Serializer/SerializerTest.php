<?php

namespace Spray\Serializer;

use DateTime;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\TestAssets\Bar;
use Spray\Serializer\TestAssets\Baz;
use Spray\Serializer\TestAssets\Foo;
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
    
    public function testFailIfSerializedIsNotAnObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->buildSerializer()->serialize('foo');
    }
    
    public function testFailIfDeserializedIsNotAClassName()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->buildSerializer()->deserialize('sdfkjsdfkjshdfjhsdf');
    }
    
    public function testSerialize()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $subject = new Foo(
            array(new Bar('foobar'), new Baz('foobar')),
            new Baz('foobar'),
            $date
        );
        $this->assertEquals(
            array(
                'bars' => array(
                    array(
                        'foobar' => 'foobar',
                        '__type' => 'Spray\Serializer\TestAssets\Bar'
                    ),
                    array(
                        'foobar' => 'foobar',
                        '__type' => 'Spray\Serializer\TestAssets\Baz'
                    )
                ),
                'baz' => array(
                    'foobar' => 'foobar',
                    '__type' => 'Spray\Serializer\TestAssets\Baz'
                ),
                'date' => '2015-01-01 12:00:00',
                '__type' => 'Spray\Serializer\TestAssets\Foo'
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testSerializeWithMissingValues()
    {
        $subject = new Foo(
            array(new Bar('foobar')),
            new Baz('foobar')
        );
        $this->assertEquals(
            array(
                'bars' => array(
                    array(
                        'foobar' => 'foobar',
                        '__type' => 'Spray\Serializer\TestAssets\Bar'
                    )
                ),
                'baz' => array(
                    'foobar' => 'foobar',
                    '__type' => 'Spray\Serializer\TestAssets\Baz'
                ),
                'date' => null,
                '__type' => 'Spray\Serializer\TestAssets\Foo'
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testDeserializeInheritedSubject()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $data = array(
            'bars' => array(
                array(
                    'foobar' => 'foobar',
                    '__type' => 'Spray\Serializer\TestAssets\Bar'
                ),
                array(
                    'foobar' => 'foobar',
                    '__type' => 'Spray\Serializer\TestAssets\Baz'
                )
            ),
            'baz' => array(
                'foobar' => 'foobar',
                '__type' => 'Spray\Serializer\TestAssets\Baz'
            ),
            'date' => '2015-01-01 12:00:00',
            '__type' => 'Spray\Serializer\TestAssets\Foo'
        );
        $this->assertEquals(
            new Foo(array(new Bar('foobar'), new Baz('foobar')), new Baz('foobar'), $date),
            $this->buildSerializer()->deserialize('Spray\Serializer\TestAssets\Foo', $data)
        );
    }
}
