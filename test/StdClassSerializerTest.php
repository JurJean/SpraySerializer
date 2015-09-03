<?php

namespace Spray\Serializer;

use PHPUnit_Framework_TestCase;
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
        $data = array('foo' => 'bar');
        $stdClass = $serializer->construct('stdClass', $data);
        $serializer->deserialize($stdClass, $data, $serializer);
        $this->assertEquals(
            $data,
            $serializer->serialize($stdClass, $data, $serializer)
        );
    }
}
