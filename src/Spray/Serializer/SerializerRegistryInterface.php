<?php

namespace Spray\Serializer;

interface SerializerRegistryInterface
{
    public function add(SerializerInterface $serializer);
    
    public function find($subject);
    
    public function contains($subject);
}
