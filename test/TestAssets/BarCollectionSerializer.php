<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class BarCollectionSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\BarCollection');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['items'] = array();
            foreach ($subject->items as $key => $value) {
                $data['items'][] = $serializer->serialize($value);
            }
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
            $subject->items = array();
            if (isset($data['items'])) {
                foreach ($data['items'] as $key => $value) {
                    $subject->items[] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['items'][$key]);
                }
            }
        };
    }
}
