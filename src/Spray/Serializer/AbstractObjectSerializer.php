<?php

namespace Spray\Serializer;

use Closure;

abstract class AbstractObjectSerializer implements SerializerInterface
{
    private $class;
    private $parents;
    private $serializer;
    private $deserializer;
    private $serializationCallback;
    private $deserializationCallback;
    
    public function __construct($serializationCallback, $deserializationCallback, $class, $parents = null)
    {
        $this->class = $class;
        $this->parents = $parents;
        $this->serializationCallback = $serializationCallback;
        $this->deserializationCallback = $deserializationCallback;
    }
    
    public function accepts($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return $subject === $this->class;
    }
    
    protected function getSerializer()
    {
        if (null === $this->serializer) {
            $this->serializer = Closure::bind($this->serializationCallback, null, $this->class);
        }
        return $this->serializer;
    }
    
    public function serialize($subject, array &$data = array())
    { 
        $serializer = $this->getSerializer();
        $serializer($subject, $data);
        return $data;
    }
    
    protected function getDeserializer()
    {
        if (null === $this->deserializer) {
            $this->deserializer = Closure::bind($this->deserializationCallback, null, $this->class);
        }
        return $this->deserializer;
    }
    
    public function deserialize($subject, array &$data = array())
    {
        $deserializer = $this->getDeserializer();
        $deserializer($subject, $data);
        return $subject;
    }
}
