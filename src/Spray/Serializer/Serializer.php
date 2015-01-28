<?php

namespace Spray\Serializer;

use ReflectionClass;
use Zend\EventManager\EventManagerInterface;

class Serializer implements SerializerInterface
{
    /**
     * @var SerializerLocatorInterface
     */
    private $serializers;
    
    /**
     * @var EventManagerInterface
     */
    private $events;
    
    public function __construct(SerializerLocatorInterface $serializers)
    {
        $this->serializers = $serializers;
    }
    
    public function accepts($subject)
    {
        return true;
    }
    
    public function construct($subject, &$data = array())
    {
        return $this->serializers->locate($subject)->construct($subject, $data);
    }
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $subject = $this->construct($subject, $data);
        foreach ($this->ancestry($subject) as $class) {
            $this->serializers->locate($class)->deserialize($subject, $data, $this);
        }
        return $subject;
    }

    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        foreach ($this->ancestry($subject) as $class) {
            $this->serializers->locate($class)->serialize($subject, $data, $this);
        }
        return $data;
    }
    
    public function ancestry($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        $result = array($subject);
        while (false !== ($subject = get_parent_class($subject))) {
            $result[] = $subject;
        }
        return $result;
    }
}
