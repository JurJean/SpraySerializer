<?php

namespace Spray\Serializer;

class Serializer implements SerializerInterface
{
    /**
     * @var SerializerLocatorInterface
     */
    public $serializers;
    
    public function __construct(SerializerLocatorInterface $serializers)
    {
        $this->serializers = $serializers;
    }
    
    /**
     * Construct $subject without calling the constructor
     * 
     * @param string $subject
     * @return object
     */
    public function construct($subject)
    {
        return unserialize(
            sprintf(
                'O:%d:"%s":0:{}',
                strlen($subject),
                $subject
            )
        );
    }
    
    public function accepts($subject)
    {
        return true;
    }
    
    public function deserialize($subject, array &$data = array(), SerializerInterface $parent = null)
    {
        $subject = $this->construct($subject);
        foreach ($this->ancestry($subject) as $class) {
            $this->serializers->locate($class)->deserialize($subject, $data, $this);
        }
        return $subject;
    }

    public function serialize($subject, array &$data = array(), SerializerInterface $parent = null)
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
