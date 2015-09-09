<?php

namespace Spray\Serializer;

use InvalidArgumentException;

class Serializer implements SerializerInterface
{
    /**
     * @var SerializerLocatorInterface
     */
    private $serializers;
    
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
        return $this->serializers->locate((string) $subject)->construct($subject, $data);
    }
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        if (isset($data['__type'])) {
            $class = $data['__type'];
        } else if (is_object($subject)) {
            $class = get_class($subject);
        } else if (is_string($subject)) {
            $class = $subject;
        } else {
            if (is_array($data) && ! isset($data['__type'])) {
                $result = array();
                foreach ($data as $item) {
                    $result[] = $this->deserialize(null, $item);
                }
                return $result;
            }
            throw new InvalidArgumentException(sprintf(
                'Could not determine class to deserialize to, %s given',
                is_object($subject) ? get_class($subject) : gettype($subject)
            ));
        }
        if ( ! class_exists($class)) {
            throw new InvalidArgumentException(sprintf(
                '% is not an existing class, %s given',
                $subject,
                is_object($subject) ? get_class($subject) : gettype($subject)
            ));
        }
        $object = $this->construct($class, $data);
        foreach ($this->ancestry($class) as $parent) {
            $this->serializers->locate($parent)->deserialize($object, $data, $this);
        }
        return $object;
    }

    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        if (is_array($subject)) {
            $result = array();
            foreach ($subject as $item) {
                $result[] = $this->serialize($item);
            }
            return $result;
        }
        if ( ! is_object($subject)) {
            throw new \InvalidArgumentException(sprintf(
                '$subject is not an object, %s given',
                is_string($subject) ? $subject : gettype($subject)
            ));
        }
        
        $data['__type'] = get_class($subject);
        foreach ($this->ancestry($data['__type']) as $parent) {
            $this->serializers->locate($parent)->serialize($subject, $data, $this);
        }
        return $data;
    }
    
    protected function ancestry($subject)
    {
        $result = array($subject);
        while (false !== ($subject = get_parent_class($subject))) {
            $result[] = $subject;
        }
        return $result;
    }
}
