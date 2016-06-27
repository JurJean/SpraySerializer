<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class SubjectSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\Subject');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['foo'] = $subject->foo;
            $data['bar'] = $subject->bar;
            $data['baz'] = $subject->baz;
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
            $subject->foo = $deserialize($subject, $data, 'foo', $subject->foo);
            $subject->bar = $deserialize($subject, $data, 'bar', $subject->bar);
            $subject->baz = $deserialize($subject, $data, 'baz', $subject->baz);
        };
    }
}
