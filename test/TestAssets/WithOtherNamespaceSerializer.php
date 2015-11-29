<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class WithOtherNamespaceSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\WithOtherNamespace');
    }
    
    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['foo'] = isset($subject->foo) ? $serializer->serialize($subject->foo) : null;
            $data['bar'] = isset($subject->bar) ? $serializer->serialize($subject->bar) : null;
        };
    }
    
    protected function bindDeserializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $subject->foo = isset($data['foo']) ? $serializer->deserialize('Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace', $data['foo']) : null;
            $subject->bar = isset($data['bar']) ? $serializer->deserialize('Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace', $data['bar']) : null;
        };
    }
}
