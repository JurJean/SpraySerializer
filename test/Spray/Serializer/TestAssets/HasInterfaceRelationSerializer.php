<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class HasInterfaceRelationSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['interface'] = isset($subject->interface) ? $serializer->serialize($subject->interface) : null;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->interface = isset($data['interface']) ? $serializer->deserialize('Spray\Serializer\TestAssets\SomeInterface', $data['interface']) : null;
            },
            'Spray\Serializer\TestAssets\HasInterfaceRelation'
        );
    }
}
