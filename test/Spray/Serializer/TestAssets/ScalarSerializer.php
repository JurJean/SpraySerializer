<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class ScalarSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['string'] = (string) $subject->string;
                $data['int'] = (int) $subject->int;
                $data['integer'] = (int) $subject->integer;
                $data['double'] = (double) $subject->double;
                $data['float'] = (float) $subject->float;
                $data['boolean'] = (bool) $subject->boolean;
                $data['bool'] = (bool) $subject->bool;
                $data['array'] = (array) $subject->array;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->string = (string) $data['string'];
                $subject->int = (int) $data['int'];
                $subject->integer = (int) $data['integer'];
                $subject->double = (double) $data['double'];
                $subject->float = (float) $data['float'];
                $subject->boolean = (bool) $data['boolean'];
                $subject->bool = (bool) $data['bool'];
                $subject->array = isset($data['array']) ? (array) $data['array'] : array();
            },
            'Spray\Serializer\TestAssets\Scalar'
        );
    }
}
