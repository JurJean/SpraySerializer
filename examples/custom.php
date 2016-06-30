<?php

use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Object\DateTimeImmutableSerializer;
use Spray\Serializer\Object\DateTimeSerializer;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\Object\StdClassSerializer;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\Serializer;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$registry = new SerializerRegistry();
$registry->add(new DateTimeSerializer());
$registry->add(new DateTimeImmutableSerializer());
$registry->add(new StdClassSerializer());

$serializer = new Serializer();
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            $registry,
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new ArrayCache('Serializer')
        )
    )
);

var_export($serializer->serialize(new DateTime()));
var_export($serializer->serialize(new DateTimeImmutable()));
var_export($serializer->serialize(new stdClass()));
