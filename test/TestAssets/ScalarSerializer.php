<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class ScalarSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\Scalar');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['string'] = (string) $subject->string;
            $data['int'] = (int) $subject->int;
            $data['integer'] = (integer) $subject->integer;
            $data['double'] = (double) $subject->double;
            $data['float'] = (float) $subject->float;
            $data['boolean'] = (boolean) $subject->boolean;
            $data['bool'] = (bool) $subject->bool;
            $data['array'] = (array) $subject->array;
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
            $subject->string = (string) $deserialize($subject, $data, 'string', $subject->string);
            $subject->int = (int) $deserialize($subject, $data, 'int', $subject->int);
            $subject->integer = (integer) $deserialize($subject, $data, 'integer', $subject->integer);
            $subject->double = (double) $deserialize($subject, $data, 'double', $subject->double);
            $subject->float = (float) $deserialize($subject, $data, 'float', $subject->float);
            $subject->boolean = (boolean) $deserialize($subject, $data, 'boolean', $subject->boolean);
            $subject->bool = (bool) $deserialize($subject, $data, 'bool', $subject->bool);
            $subject->array = (array) $deserialize($subject, $data, 'array', $subject->array);
        };
    }
}
