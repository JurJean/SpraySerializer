<?php

namespace Spray\Serializer;

use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;

final class SerializerFactory
{
    /**
     * @return Serializer
     */
    static function simple()
    {
        $serializer = new Serializer();
        $serializer->attach(
            new ObjectListener(
                new SerializerLocator(
                    new SerializerRegistry(),
                    new ObjectSerializerGenerator(
                        new AnnotationBackedPropertyInfo()
                    ),
                    new ArrayCache('Serializer')
                )
            )
        );
        return $serializer;
    }
}
