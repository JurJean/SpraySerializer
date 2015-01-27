<?php

namespace Spray\Serializer;

interface SerializerInterface
{
    public function accepts($subject);
    
    public function serialize($subject, array &$data = array());
    
    public function deserialize($subject, array &$data = array());
}
