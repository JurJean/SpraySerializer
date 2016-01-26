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
        $value = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($value) {
            $subject->foo = $value($subject, $data, 'foo', $subject->foo);
            $subject->bar = $value($subject, $data, 'bar', $subject->bar);
            $subject->baz = $value($subject, $data, 'baz', $subject->baz);
        };
    }
}
