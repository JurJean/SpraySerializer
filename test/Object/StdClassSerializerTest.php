<?php

namespace Spray\Serializer\Object;

use PHPUnit_Framework_TestCase;
use Spray\Serializer\Object\StdClassSerializer;
use stdClass;

class StdClassSerializerTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $serializer = new StdClassSerializer;
        $data = array();

        $this->assertEquals(
            new stdClass,
            $serializer->construct('stdClass', $data)
        );
    }

    public function testSerializeDeserializeYieldsSameResult()
    {
        $serializer = new StdClassSerializer;
        $data = array('foo' => 'bar', '__type' => 'stdClass');
        $stdClass = $serializer->construct('stdClass', $data);
        $serializer->deserialize($stdClass, $data, $serializer);
        $this->assertFalse(isset($stdClass->__type));
        $this->assertEquals(
            $data,
            $serializer->serialize($stdClass, $data, $serializer)
        );
    }
}
