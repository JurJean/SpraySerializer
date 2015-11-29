<?php

namespace Spray\Serializer\Object;

use DateTime;
use PHPUnit_Framework_TestCase;
use Spray\Serializer\Object\DateTimeSerializer;

class DateTimeSerializerTest extends PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $serializer = new DateTimeSerializer;
        $data = array();
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00');
        $this->assertSame(
            '2015-01-01 12:00:00',
            $serializer->serialize($dateTime, $data, $serializer)
        );
    }
    
    public function testConstruct()
    {
        $serializer = new DateTimeSerializer;
        $data = '2015-01-01 12:00:00';
        $this->assertEquals(
            DateTime::createFromFormat('Y-m-d H:i:s', '2015-01-01 12:00:00'),
            $serializer->construct('DateTime', $data)
        );
    }
    
    public function testDeserializeReturnsConstructedDateTime()
    {
        $serializer = new DateTimeSerializer;
        $data = '2015-01-01 12:00:00';
        $constructed = $serializer->construct('DateTime', $data);
        $this->assertSame(
            $constructed,
            $serializer->deserialize($constructed, $data, $serializer)
        );
    }
}
