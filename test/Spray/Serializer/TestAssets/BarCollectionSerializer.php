<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class BarCollectionSerializer extends AbstractObjectSerializer
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
                $data['items'][$key] = $serializer->serialize($value);
            }
        };
    }
    
    protected function bindDeserializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $subject->items = array();
            if (isset($data['items'])) {
                foreach ($data['items'] as $key => $value) {
                    $subject->items[$key] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['items'][$key]);
                }
            }
        };
    }
}
