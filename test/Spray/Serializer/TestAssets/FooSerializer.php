<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class FooSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['bars'] = array();
                foreach ($subject->bars as $key => $value) {
                    $data['bars'][$key] = $serializer->serialize($value);
                }
                $data['baz'] = $serializer->serialize($subject->baz);
                $data['date'] = $serializer->serialize($subject->date);
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->bars = array();
                foreach ($data['bars'] as $key => $value) {
                    $subject->bars[$key] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['bars'][$key]);
                }
                $subject->baz = $serializer->deserialize('Spray\Serializer\TestAssets\Baz', $data['baz']);
                $subject->date = $serializer->deserialize('DateTime', $data['date']);
            },
            'Spray\Serializer\TestAssets\Foo'
        );
    }
}
