<?php

namespace Spray\Serializer;

use DateTime;
use PHPUnit_Framework_TestCase;

class DateTimeImmutableSerializerTest extends PHPUnit_Framework_TestCase
{
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
