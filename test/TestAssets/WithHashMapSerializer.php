<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class WithHashMapSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\WithHashMap');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['stringsArray'] = (array) $subject->stringsArray;
            $data['objectsArray'] = array();
            foreach ($subject->objectsArray as $key => $value) {
                $data['objectsArray'][] = $serializer->serialize($value);
            }
            $data['stringsHash'] = array();
            foreach ($subject->stringsHash as $key => $value) {
                $data['stringsHash'][$key] = $value;
            }
            $data['objectsHash'] = array();
            foreach ($subject->objectsHash as $key => $value) {
                $data['objectsHash'][$key] = $serializer->serialize($value);
            }
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
            $subject->stringsArray = (array) $deserialize($subject, $data, 'stringsArray', $subject->stringsArray);
            $subject->objectsArray = array();
            if (isset($data['objectsArray'])) {
                foreach ($data['objectsArray'] as $key => $value) {
                    $subject->objectsArray[] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['objectsArray'][$key]);
                }
            }
            $subject->stringsHash = array();
            if (isset($data['stringsHash'])) {
                foreach ($data['stringsHash'] as $key => $value) {
                    $subject->stringsHash[$key] = (string) $value;
                }
            }
            $subject->objectsHash = array();
            if (isset($data['objectsHash'])) {
                foreach ($data['objectsHash'] as $key => $value) {
                    $subject->objectsHash[$key] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['objectsHash'][$key]);
                }
            }
        };
    }
}
