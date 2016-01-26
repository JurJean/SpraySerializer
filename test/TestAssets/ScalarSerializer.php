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
            $data['integer'] = (int) $subject->integer;
            $data['double'] = (double) $subject->double;
            $data['float'] = (float) $subject->float;
            $data['boolean'] = (bool) $subject->boolean;
            $data['bool'] = (bool) $subject->bool;
            $data['array'] = (array) $subject->array;
            $data['unknown'] = $subject->unknown;
        };
    }

    protected function bindDeserializer()
    {
        $value = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($value) {
            $subject->string = (string) $value($subject, $data, 'string', $subject->string);
            $subject->int = (int) $value($subject, $data, 'int', $subject->int);
            $subject->integer = (int) $value($subject, $data, 'integer', $subject->integer);
            $subject->double = (double) $value($subject, $data, 'double', $subject->double);
            $subject->float = (float) $value($subject, $data, 'float', $subject->float);
            $subject->boolean = (bool) $value($subject, $data, 'boolean', $subject->boolean);
            $subject->bool = (bool) $value($subject, $data, 'bool', $subject->bool);
            $subject->array = (array) $value($subject, $data, 'array', $subject->array);
            $subject->unknown = $value($subject, $data, 'unknown', $subject->unknown);
        };
    }
}
