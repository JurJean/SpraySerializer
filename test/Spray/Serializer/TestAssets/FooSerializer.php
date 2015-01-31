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
                $data['bars'] = isset($subject->bars) ? $serializer->serialize($subject->bars) : null;
                $data['baz'] = isset($subject->baz) ? $serializer->serialize($subject->baz) : null;
                $data['date'] = isset($subject->date) ? $serializer->serialize($subject->date) : null;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->bars = isset($data['bars']) ? $serializer->deserialize('Spray\Serializer\TestAssets\BarCollection', $data['bars']) : null;
                $subject->baz = isset($data['baz']) ? $serializer->deserialize('Spray\Serializer\TestAssets\Baz', $data['baz']) : null;
                $subject->date = isset($data['date']) ? $serializer->deserialize('DateTime', $data['date']) : null;
            },
            'Spray\Serializer\TestAssets\Foo'
        );
    }
}
