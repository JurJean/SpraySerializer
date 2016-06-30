<?php

use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\ObjectTypeListener;
use Spray\Serializer\Serializer;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$serializer = new Serializer();
$serializer->attach(
    new ObjectTypeListener(),
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

var_export($serializer->serialize(new SerializeMe()));

class ExtendMe
{

}

class SerializeMe extends ExtendMe
{

}
