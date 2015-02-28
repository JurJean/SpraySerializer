<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class WithOtherNamespaceSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['foo'] = isset($subject->foo) ? $serializer->serialize($subject->foo) : null;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->foo = isset($data['foo']) ? $serializer->deserialize('Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace', $data['foo']) : null;
            },
            'Spray\Serializer\TestAssets\WithOtherNamespace'
        );
    }
}
