<?php

namespace Spray\Serializer;

interface SerializerInterface
{
    public function accepts($subject);
    
    public function serialize($subject, array &$data = array(), SerializerInterface $serializer = null);
    
    public function deserialize($subject, array &$data = array(), SerializerInterface $serializer = null);
}
