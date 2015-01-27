<?php

namespace Spray\Serializer;

use Closure;

abstract class AbstractObjectSerializer implements SerializerInterface
{
    private $class;
    private $serializer;
    private $deserializer;
    private $serializationCallback;
    private $deserializationCallback;
    
    public function __construct($serializationCallback, $deserializationCallback, $class)
    {
        $this->class = $class;
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
    
    public function serialize($subject, array &$data = array(), SerializerInterface $parent = null)
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
    
    public function deserialize($subject, array &$data = array(), SerializerInterface $parent = null)
    {
        $deserializer = $this->getDeserializer();
        $deserializer($subject, $data);
        return $subject;
    }
}
