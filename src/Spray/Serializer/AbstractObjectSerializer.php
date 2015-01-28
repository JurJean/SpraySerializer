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
        var_dump($subject);
        var_dump($this->class);
        return $subject === $this->class;
    }
    
    protected function getSerializer()
    {
        if (null === $this->serializer) {
            $this->serializer = Closure::bind($this->serializationCallback, null, $this->class);
        }
        return $this->serializer;
    }
    
    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    { 
        $serialize = $this->getSerializer();
        $serialize($subject, $data, $serializer);
        return $data;
    }
    
    protected function getDeserializer()
    {
        if (null === $this->deserializer) {
            $this->deserializer = Closure::bind($this->deserializationCallback, null, $this->class);
        }
        return $this->deserializer;
    }
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $deserialize = $this->getDeserializer();
        $deserialize($subject, $data, $serializer);
        return $subject;
    }
}
