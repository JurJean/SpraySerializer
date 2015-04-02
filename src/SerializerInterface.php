<?php

namespace Spray\Serializer;

interface SerializerInterface
{
    public function accepts($subject);
    
    public function construct($subject, &$data = array());
    
    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null);
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null);
}
