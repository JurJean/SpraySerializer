<?php


use Spray\Serializer\Object\BoundClosureSerializer;
use Spray\Serializer\SerializerInterface;

class CacheMeSerializer extends BoundClosureSerializer
{
    public function __construct()
    {
        parent::__construct('CacheMe');
    }

    protected function bindSerializer()
    {
        return function($subject, array &$data, SerializerInterface $serializer) {
        };
    }

    protected function bindDeserializer()
    {
        $deserialize = $this->valueDeserializer();
        return function($subject, array &$data, SerializerInterface $serializer) use ($deserialize) {
        };
    }
}
