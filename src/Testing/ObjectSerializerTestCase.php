<?php

namespace Spray\Serializer\Testing;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\DateTimeImmutableSerializer;
use Spray\Serializer\DateTimeSerializer;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\ObjectSerializerBuilder;
use Spray\Serializer\ObjectTypeListener;
use Spray\Serializer\ReflectionRegistry;
use Spray\Serializer\Serializer;
use Spray\Serializer\SerializerLocator;
use Spray\Serializer\SerializerRegistry;
use Zend\EventManager\EventManager;

abstract class ObjectSerializerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Implement to return an array of objects to (de)serialize.
     *
     * @return array<object>
     */
    abstract public function createObjectsToSerialize();

    /**
     * @return Serializer
     */
    public function createSerializer()
    {
        $registry = new SerializerRegistry();
        $registry->add(new DateTimeSerializer());
        $registry->add(new DateTimeImmutableSerializer());

        $serializer = new Serializer(new EventManager());
        $serializer->attach(new ObjectTypeListener());
        $serializer->attach(new ObjectListener(new SerializerLocator(
            $registry,
            new ObjectSerializerBuilder(new ReflectionRegistry()),
            new ArrayCache()
        )));

        return $serializer;
    }

    public function testObjectIsEqualAfterSerializeAndDeserialize()
    {
        $serializer = $this->createSerializer();
        foreach ($this->createObjectsToSerialize() as $object) {
            $serialized = $serializer->serialize($object);
            $this->assertEquals($object, $serializer->deserialize(null, $serialized));
        }
    }
}