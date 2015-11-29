<?php

namespace Spray\Serializer\Object;

use Spray\Serializer\SerializerInterface;

interface SerializerRegistryInterface
{
    public function add(SerializerInterface $serializer);
    
    public function find($subject);
    
    public function contains($subject);
}
