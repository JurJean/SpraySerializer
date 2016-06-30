<?php

use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\Serializer;

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
            new ArrayCache('Serializer')
        )
    )
);

var_export($serializer->serialize(new SerializeMe()));

class SerializeMe
{
    /**
     * @var string
     */
    private $string;

    /**
     * @var int
     */
    private $int;

    /**
     * @var integer
     */
    private $integer;

    /**
     * @var bool
     */
    private $bool;

    /**
     * @var boolean
     */
    private $boolean;

    /**
     * @var float
     */
    private $float;

    /**
     * @var double
     */
    private $double;

    /**
     * @var array
     */
    private $array;

    /**
     * @var stdClass
     */
    private $object;

    /**
     * @var string[]
     */
    private $stringArray = [];

    /**
     * @var stdClass[]
     */
    private $objectArray = [];

    /**
     * @var array<string>
     */
    private $stringArrayJavaStyle = [];

    /**
     * @var array<stdClass>
     */
    private $objectArrayJavaStyle = [];
}
