<?php

use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Cache\FileCache;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\Serializer;
use Symfony\Component\Filesystem\Filesystem;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$serializer = new Serializer();
$serializer->attach(
    new ObjectListener(
        new SerializerLocator(
            new SerializerRegistry(),
            new ObjectSerializerGenerator(
                new AnnotationBackedPropertyInfo()
            ),
            new FileCache(new Filesystem(), __DIR__, 'Serializer')
        )
    )
);

var_export($serializer->serialize(new CacheMe()));
echo file_get_contents(__DIR__ . '/CacheMeSerializer.php');

class CacheMe
{

}
