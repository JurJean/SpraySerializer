<?php

namespace Spray\Serializer;

use DateTime;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\TestAssets\Bar;
use Spray\Serializer\TestAssets\BarCollection;
use Spray\Serializer\TestAssets\Baz;
use Spray\Serializer\TestAssets\Foo;
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
            new BarCollection(array(
                new Bar('foobar'),
                new Baz('foobar')
            )),
            new Baz('foobar'),
            $date
        );
        $this->assertEquals(
            array(
                'bars' => array(
                    'items' => array(
                        array(
                            'foobar' => 'foobar',
                            '__type' => 'Spray\Serializer\TestAssets\Bar'
                        ),
                        array(
                            'foobar' => 'foobar',
                            'arrays' => array(
                                'key' => 'value',
                                'array' => array(
                                    'key' => 'value'
                                )
                            ),
                            '__type' => 'Spray\Serializer\TestAssets\Baz'
                        )
                    ),
                    '__type' => 'Spray\Serializer\TestAssets\BarCollection'
                ),
                'baz' => array(
                    'foobar' => 'foobar',
                    'arrays' => array(
                        'key' => 'value',
                        'array' => array(
                            'key' => 'value'
                        )
                    ),
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
            new BarCollection(array(new Bar('foobar'))),
            new Baz('foobar')
        );
        $this->assertEquals(
            array(
                'bars' => array(
                    'items' => array(
                        array(
                            'foobar' => 'foobar',
                            '__type' => 'Spray\Serializer\TestAssets\Bar'
                        )
                    ),
                    '__type' => 'Spray\Serializer\TestAssets\BarCollection'
                ),
                'baz' => array(
                    'foobar' => 'foobar',
                    'arrays' => array(
                        'key' => 'value',
                        'array' => array(
                            'key' => 'value'
                        )
                    ),
                    '__type' => 'Spray\Serializer\TestAssets\Baz'
                ),
                'date' => null,
                '__type' => 'Spray\Serializer\TestAssets\Foo'
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testSerializeWithNoAnnotations()
    {
        $subject = new Subject('foo', 'bar', 'baz');
        $this->assertEquals(
            array(
                'foo'    => 'foo',
                'bar'    => 'bar',
                'baz'    => 'baz',
                '__type' => 'Spray\Serializer\TestAssets\Subject',
            ),
            $this->buildSerializer()->serialize($subject)
        );
    }
    
    public function testDeserializeInheritedSubject()
    {
        $date = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $data = array(
            'bars' => array(
                'items' => array(
                    array(
                        'foobar' => 'foobar',
                        '__type' => 'Spray\Serializer\TestAssets\Bar'
                    ),
                    array(
                        'foobar' => 'foobar',
                        'arrays' => array(
                            'key' => 'value',
                            'array' => array(
                                'key' => 'value'
                            )
                        ),
                        '__type' => 'Spray\Serializer\TestAssets\Baz'
                    )
                ),
                '__type' => 'Spray\Serializer\TestAssets\BarCollection'
            ),
            'baz' => array(
                'foobar' => 'foobar',
                'arrays' => array(
                    'key' => 'value',
                    'array' => array(
                        'key' => 'value'
                    )
                ),
                '__type' => 'Spray\Serializer\TestAssets\Baz'
            ),
            'date' => '2015-01-01 12:00:00',
            '__type' => 'Spray\Serializer\TestAssets\Foo'
        );
        $this->assertEquals(
            new Foo(
                new BarCollection(array(
                    new Bar('foobar'),
                    new Baz('foobar')
                )),
                new Baz('foobar'),
                $date
            ),
            $this->buildSerializer()->deserialize('Spray\Serializer\TestAssets\Foo', $data)
        );
    }
    
    public function testDeserializeEmptyDateTime()
    {
        $expected = new Foo();
        $data = array(
            'date' => null,
        );
        $this->assertEquals(
            $this->buildSerializer()->deserialize(
                'Spray\Serializer\TestAssets\Foo',
                $data
            ),
            $expected
        );
    }
}
