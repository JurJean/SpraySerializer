<?php

namespace Spray\Serializer;

use DateTime;
use DateTimeImmutable;
use Spray\Serializer\Object\Exception\MissingDataException;
use Spray\Serializer\TestAssets\Bar;
use Spray\Serializer\TestAssets\BarCollection;
use Spray\Serializer\TestAssets\Baz;
use Spray\Serializer\TestAssets\Foo;
use Spray\Serializer\TestAssets\HasDateTimeImmutable;
use Spray\Serializer\TestAssets\Ignore;
use Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace;
use Spray\Serializer\TestAssets\Subject;
use Spray\Serializer\TestAssets\WithDefaultValue;
use Spray\Serializer\TestAssets\WithHashMap;
use Spray\Serializer\TestAssets\WithOtherNamespace;
use Spray\Serializer\Testing\ObjectSerializerTestCase;

class SerializerTest extends ObjectSerializerTestCase
{
    public function testFailIfSerializedIsNotAnObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->createSerializer()->serialize('foo');
    }
    
    public function testFailIfDeserializedIsNotAClassName()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->createSerializer()->deserialize('sdfkjsdfkjshdfjhsdf');
    }

    /**
     * Implement to return an array of objects to (de)serialize.
     *
     * @return array<object>
     */
    public function createObjectsToSerialize()
    {
        $objects = [
            new Foo,
            new Foo(
                new BarCollection(array(new Bar('foobar'))),
                new Baz('foobar')
            ),
            new Foo(
                new BarCollection(array(
                    new Bar('foobar'),
                    new Baz('foobar')
                )),
                new Baz('foobar'),
                DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00')
            ),
            new BarCollection(array()),
            new WithOtherNamespace(new InOtherNamespace('foo'), new InOtherNamespace('bar')),
            new WithDefaultValue(),
            new WithHashMap()
        ];

        if (class_exists('DateTimeImmutable')) {
            $objects[] = new HasDateTimeImmutable(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2011-01-01 12:00:00'));
        }

        return $objects;
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
            $date,
            new Ignore()
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
                'ignore' => [
                    '__type' => 'Spray\Serializer\TestAssets\Ignore'
                ],
                '__type' => 'Spray\Serializer\TestAssets\Foo'
            ),
            $this->createSerializer()->serialize($subject)
        );
    }


    public function testDeserializeMissingData()
    {
        $this->setExpectedException(
            MissingDataException::class,
            'Data is missing for Spray\Serializer\TestAssets\Bar::$foobar, received keys: saanka, kaas'
        );

        $data = ['saanka' => '', 'kaas' => ''];

        $this->createSerializer()->deserialize(Bar::class, $data);
    }


    public function testSerializationSpeed()
    {
        if (getenv('TRAVIS')) {
            $this->markTestSkipped('Takes too long on travis');
        }

        $count = 0;
        $serializer = $this->createSerializer();
        $time = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            foreach ($this->createObjectsToSerialize() as $object) {
                $count++;
                $data = $serializer->serialize($object);
                $serializer->deserialize(get_class($object), $data);
            }
        }
        $this->assertLessThan(
            1.5,
            microtime(true) - $time,
            sprintf('(de)serialization of %s objects took too long', $count)
        );
    }
}
