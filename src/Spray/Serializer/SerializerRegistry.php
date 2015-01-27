<?php

namespace Spray\Serializer;

use RuntimeException;

class SerializerRegistry implements SerializerRegistryInterface
{
    private $serializers = array();
    
    public function add(SerializerInterface $serializer)
    {
        $this->serializers[] = $serializer;
    }
    
    public function find($subject)
    {
        foreach ($this->serializers as $serializer) {
            if ( ! $serializer->accepts($subject)) {
                continue;
            }
            return $serializer;
        }
        throw new RuntimeException(sprintf(
            'No serializer for %s',
            $subject
        ));
    }
    
    public function contains($subject)
    {
        foreach ($this->serializers as $serializer) {
            if ( ! $serializer->accepts($subject)) {
                continue;
            }
            return true;
        }
        return false;
    }
}
