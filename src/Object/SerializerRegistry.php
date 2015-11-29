<?php

namespace Spray\Serializer\Object;

use RuntimeException;
use Spray\Serializer\SerializerInterface;
use Spray\Serializer\Object\SerializerRegistryInterface;

class SerializerRegistry implements SerializerRegistryInterface
{
    /**
     * @var array<int,SerializerInterface>
     */
    private $serializers = array();
    
    /**
     * @var array<string,int>
     */
    private $found = array();
    
    /**
     * Add a serializer
     * 
     * @param SerializerInterface $serializer
     */
    public function add(SerializerInterface $serializer)
    {
        $this->serializers[] = $serializer;
    }
    
    /**
     * Find a serializer for $subject, a class name.
     * 
     * @param string $subject
     * @return SerializerInterface
     * @throws RuntimeException if no serializer accepts $subject
     */
    public function find($subject)
    {
        if (isset($this->found[$subject])) {
            return $this->serializers[$this->found[$subject]];
        }
        foreach ($this->serializers as $i => $serializer) {
            if ( ! $serializer->accepts($subject)) {
                continue;
            }
            $this->found[$subject] = $i;
            return $serializer;
        }
        throw new RuntimeException(sprintf(
            'No serializer for %s',
            $subject
        ));
    }
    
    /**
     * Check if a serializer accepts $subject
     * 
     * @param string $subject
     * @return boolean
     */
    public function contains($subject)
    {
        if (isset($this->found[$subject])) {
            return true;
        }
        foreach ($this->serializers as $i => $serializer) {
            if ( ! $serializer->accepts($subject)) {
                continue;
            }
            $this->found[$subject] = $i;
            return true;
        }
        return false;
    }
}
