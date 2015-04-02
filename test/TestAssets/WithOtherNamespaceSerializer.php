<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class WithOtherNamespaceSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\WithOtherNamespace');
    }
    
    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['foo'] = isset($subject->foo) ? $serializer->serialize($subject->foo) : null;
        };
    }
    
    protected function bindDeserializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $subject->foo = isset($data['foo']) ? $serializer->deserialize('Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace', $data['foo']) : null;
        };
    }
}
