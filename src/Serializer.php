<?php

namespace Spray\Serializer;

use InvalidArgumentException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Serializer implements SerializerInterface
{
    /**
     * @var EventManagerInterface
     */
    private $events;

    /**
     * @var SerializerLocatorInterface
     */
    private $serializers;

    /**
     * @param EventManagerInterface $events
     */
    public function __construct(EventManagerInterface $events)
    {
        $this->events = $events;
    }

    public function attach(ListenerAggregateInterface $listener)
    {
        $this->events->attachAggregate($listener);
    }
    
    public function accepts($subject)
    {
        return true;
    }
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $event = new SerializeEvent($subject, $data, $this);
        $this->events->trigger(SerializeEvent::CONSTRUCT, $event);
        $this->events->trigger(SerializeEvent::INJECT, $event);
        return $event->getSubject();

//        if (is_array($data) && ! isset($data['__type'])) {
//            $result = array();
//            foreach ($data as $item) {
//                $result[] = $this->deserialize(null, $item);
//            }
//            return $result;
//        }
//        throw new InvalidArgumentException(sprintf(
//            'Could not determine class to deserialize to, %s given',
//            is_object($subject) ? get_class($subject) : gettype($subject)
//        ));
    }

    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $event = new SerializeEvent($subject, $data, $this);
        $this->events->trigger(SerializeEvent::DESTRUCT, $event);
        $this->events->trigger(SerializeEvent::EXTRACT, $event);
        return $event->getData();

//        if (is_array($subject)) {
//            $result = array();
//            foreach ($subject as $item) {
//                $result[] = $this->serialize($item);
//            }
//            return $result;
//        }
    }

}
