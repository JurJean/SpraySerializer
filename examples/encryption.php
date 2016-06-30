<?php

use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Encryption\EncryptorGenerator;
use Spray\Serializer\Encryption\EncryptorLocator;
use Spray\Serializer\EncryptionListener;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\ObjectListener;
use Spray\Serializer\Serializer;
use Zend\Crypt\BlockCipher;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

$blockCipher = BlockCipher::factory('mcrypt', ['algo' => 'aes']);
$blockCipher->setKey('5eDCZRmyX8s7nbgV9f6pVrmRISdc5t8L');

$serializer = new Serializer();
$serializer->attach(
    new EncryptionListener(
        new EncryptorLocator(
            new EncryptorGenerator(new AnnotationBackedPropertyInfo()),
            new ArrayCache('Encryptor')
        ),
        $blockCipher
    )
);
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

var_export($serializer->serialize(new EncryptMe()));

class EncryptMe
{
    /**
     * @var string
     * @private
     */
    private $secret = 'I\'m secret';
}
