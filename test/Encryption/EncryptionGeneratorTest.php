<?php

namespace Spray\Serializer\Encryption;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\AnnotationBackedPropertyInfo;
use Spray\Serializer\TestAssets\PrivateStuff;

class EncryptionGeneratorTest extends PHPUnit_Framework_TestCase
{
    public function testGenerateEncryption()
    {
        $generator = new EncryptorGenerator(new AnnotationBackedPropertyInfo());

        $this->assertEquals(
            file_get_contents(dirname(__DIR__) . '/TestAssets/PrivateStuffEncryptor.php'),
            $generator->generate(PrivateStuff::class)
        );
    }
}
