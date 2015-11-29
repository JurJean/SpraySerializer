<?php

namespace Spray\Serializer\Object;

use DateTimeImmutable;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Object\DateTimeImmutableSerializer;

class DateTimeImmutableSerializerTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        if (version_compare(phpversion(), '5.5.0', '<')) {
            $this->markTestSkipped('This php version does not contain DateTimeImmutable');
        }
    }
    
    public function testSerialize()
    {
        $serializer = new DateTimeImmutableSerializer;
        $data = array();
        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $this->assertSame(
            '2015-01-01 12:00:00',
            $serializer->serialize($dateTime, $data, $serializer)
        );
    }
    
    public function testConstruct()
    {
        $serializer = new DateTimeImmutableSerializer;
        $data = '2015-01-01 12:00:00';
        $this->assertEquals(
            DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00'),
            $serializer->construct('DateTimeImmutable', $data)
        );
    }
    
    public function testDeserializeReturnsConstructedDateTime()
    {
        $serializer = new DateTimeImmutableSerializer;
        $data = '2015-01-01 12:00:00';
        $constructed = $serializer->construct('DateTimeImmutable', $data);
        $this->assertSame(
            $constructed,
            $serializer->deserialize($constructed, $data, $serializer)
        );
    }
}
