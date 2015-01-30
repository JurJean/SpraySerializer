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
                $data['baz'] = isset($subject->baz) ? $serializer->serialize($subject->baz) : null;
                $data['date'] = isset($subject->date) ? $serializer->serialize($subject->date) : null;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->bars = array();
                foreach ($data['bars'] as $key => $value) {
                    $subject->bars[$key] = $serializer->deserialize('Spray\Serializer\TestAssets\Bar', $data['bars'][$key]);
                }
                $subject->baz = isset($data['baz']) ? $serializer->deserialize('Spray\Serializer\TestAssets\Baz', $data['baz']) : null;
                $subject->date = isset($data['date']) ? $serializer->deserialize('DateTime', $data['date']) : null;
            },
            'Spray\Serializer\TestAssets\Foo'
        );
    }
}
