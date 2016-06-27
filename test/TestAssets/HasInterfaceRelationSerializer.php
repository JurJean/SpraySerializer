<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class HasInterfaceRelationSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\HasInterfaceRelation');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['interface'] = isset($subject->interface) ? $serializer->serialize($subject->interface) : null;
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
            $subject->interface = isset($data['interface']) ? $serializer->deserialize('Spray\Serializer\TestAssets\SomeInterface', $data['interface']) : null;
        };
    }
}
