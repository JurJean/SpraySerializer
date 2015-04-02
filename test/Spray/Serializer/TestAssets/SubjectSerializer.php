<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class SubjectSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct('Spray\Serializer\TestAssets\Subject');
    }
    
    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $data['foo'] = $subject->foo;
            $data['bar'] = $subject->bar;
            $data['baz'] = $subject->baz;
        };
    }
    
    protected function bindDeserializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
            $subject->foo = $data['foo'];
            $subject->bar = $data['bar'];
            $subject->baz = $data['baz'];
        };
    }
}
