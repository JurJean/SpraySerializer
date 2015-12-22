<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\Cache\ArrayCache;
use Spray\Serializer\Encryption\EncryptorGenerator;
use Spray\Serializer\Encryption\EncryptorLocator;
use Spray\Serializer\Object\ObjectSerializerGenerator;
use Spray\Serializer\Object\SerializerLocator;
use Spray\Serializer\Object\SerializerRegistry;
use Spray\Serializer\TestAssets\PrivateStuff;
use Zend\Crypt\BlockCipher;
use Zend\EventManager\EventManager;

class EncryptionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var BlockCipher
     */
    private $blockCipher;

    protected function setUp()
    {
        $this->blockCipher = $this->getMockBuilder(BlockCipher::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function createSerializer()
    {
        $registry = new SerializerRegistry();

        $serializer = new Serializer(new EventManager());
        $serializer->attach(new ObjectListener(new SerializerLocator(
            $registry,
            new ObjectSerializerGenerator(new AnnotationBackedPropertyInfo()),
            new ArrayCache('Serializer')
        )));
        $serializer->attach(new EncryptionListener(
            new EncryptorLocator(
                new EncryptorGenerator(new AnnotationBackedPropertyInfo()),
                new ArrayCache('Encryptor')
            ),
            $this->blockCipher
        ));

        return $serializer;
    }

    public function testEncrypt()
    {
        $this->blockCipher->expects($this->at(0))
            ->method('encrypt')
            ->with('Foo')
            ->will($this->returnValue('Encrypted 1'));
        $this->blockCipher->expects($this->at(1))
            ->method('encrypt')
            ->with('["Foo"]')
            ->will($this->returnValue('Encrypted 2'));

        $this->assertEquals(
            array(
                'noSecret' => 'Foo',
                'secretString' => 'Encrypted 1',
                'secretArray' => 'Encrypted 2'
            ),
            $this->createSerializer()->serialize(new PrivateStuff('Foo', 'Foo', ['Foo']))
        );
    }

    public function testDecrypt()
    {
        $this->blockCipher->expects($this->at(0))
            ->method('decrypt')
            ->with('Encrypted 1')
            ->will($this->returnValue('Foo'));
        $this->blockCipher->expects($this->at(1))
            ->method('decrypt')
            ->with('Encrypted 2')
            ->will($this->returnValue('["Foo"]'));

        $data = array(
            'noSecret' => 'Foo',
            'secretString' => 'Encrypted 1',
            'secretArray' => 'Encrypted 2'
        );
        $this->assertEquals(
            new PrivateStuff('Foo', 'Foo', ['Foo']),
            $this->createSerializer()->deserialize(PrivateStuff::class, $data)
        );
    }

    public function testEncryptEmpty()
    {
        $this->blockCipher->expects($this->never())
            ->method('encrypt');

        $this->assertEquals(
            array(
                'noSecret' => 'Foo',
                'secretString' => null,
                'secretArray' => null
            ),
            $this->createSerializer()->serialize(new PrivateStuff('Foo', '', []))
        );
    }

    public function testDecryptEmpty()
    {
        $this->blockCipher->expects($this->never())
            ->method('decrypt');

        $data = array(
            'noSecret' => 'Foo',
            'secretString' => null,
            'secretArray' => null
        );

        $this->assertEquals(
            new PrivateStuff('Foo', null, []),
            $this->createSerializer()->deserialize(PrivateStuff::class, $data)
        );
    }
}
