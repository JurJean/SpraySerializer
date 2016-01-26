<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class WithDefaultValueSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\WithDefaultValue');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['foo'] = (string) $subject->foo;
        };
    }

    protected function bindDeserializer()
    {
        $value = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($value) {
            $subject->foo = (string) $value($subject, $data, 'foo', $subject->foo);
        };
    }
}
