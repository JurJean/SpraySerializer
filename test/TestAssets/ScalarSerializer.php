<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\BoundClosureSerializer;
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
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['string'] = isset($data['string']) ? (string) $data['string'] : null;
            $data['int'] = isset($data['int']) ? (int) $data['int'] : null;
            $data['integer'] = isset($data['integer']) ? (int) $data['integer'] : null;
            $data['double'] = isset($data['double']) ? (double) $data['double'] : null;
            $data['float'] = isset($data['float']) ? (float) $data['float'] : null;
            $data['boolean'] = isset($data['boolean']) ? (bool) $data['boolean'] : null;
            $data['bool'] = isset($data['bool']) ? (bool) $data['bool'] : null;
            $subject->array = isset($data['array']) ? (array) $data['array'] : array();
            $subject->unknown = $data['unknown'];
        };
    }
}
