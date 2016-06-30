<?php

namespace Spray\Serializer;

use InvalidArgumentException;
use Spray\Serializer\Object\SerializerLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class Serializer implements SerializerInterface
{
    /**
     * @var EventManagerInterface
     */
    private $events;

    /**
     * @param EventManagerInterface $events
     */
    public function __construct(EventManagerInterface $events = null)
    {
        if (null === $events) {
            $this->events = new EventManager();
        } else {
            $this->events = $events;
        }
    }

    public function attach(ListenerAggregateInterface $listener)
    {
        $listener->attach($this->events);
    }
    
    public function accepts($subject)
    {
        return true;
    }
    
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $event = new SerializeEvent($subject, $data, $this);

        $event->setName(SerializeEvent::CONSTRUCT);
        $this->events->triggerEvent($event);

        $event->setName(SerializeEvent::INJECT);
        $this->events->triggerEvent($event);

        return $event->getSubject();
    }

    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $event = new SerializeEvent($subject, $data, $this);

        $event->setName(SerializeEvent::DESTRUCT);
        $this->events->triggerEvent($event);

        $event->setName(SerializeEvent::EXTRACT);
        $this->events->triggerEvent($event);

        return $event->getData();
    }
}
