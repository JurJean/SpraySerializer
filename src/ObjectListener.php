<?php

namespace Spray\Serializer;

use InvalidArgumentException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ObjectListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var SerializerLocatorInterface
     */
    private $serializers;

    /**
     * @param SerializerLocatorInterface $serializers
     */
    public function __construct(SerializerLocatorInterface $serializers)
    {
        $this->serializers = $serializers;
    }

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(SerializeEvent::CONSTRUCT, array($this, 'construct'));
        $this->listeners[] = $events->attach(SerializeEvent::INJECT, array($this, 'inject'));
        $this->listeners[] = $events->attach(SerializeEvent::EXTRACT, array($this, 'extract'));
    }

    public function construct(SerializeEvent $event)
    {
        $subject = $event->getSubject();
        $data = &$event->getData();

        if ( ! class_exists($subject)) {
            throw new InvalidArgumentException(sprintf(
                '$subject is expected to be an existing class, %s given',
                $subject
            ));
        }

        $constructor = $this->serializers->locate($subject);
        if ( ! $constructor instanceof ConstructorInterface) {
            return;
        }

        $event->setSubject($constructor->construct($subject, $data));
    }

    public function inject(SerializeEvent $event)
    {
        $subject = $event->getSubject();
        $data = &$event->getData();
        $serializer = $event->getSerializer();

        foreach ($this->ancestry(get_class($subject)) as $parent) {
            $this->serializers->locate($parent)->deserialize($subject, $data, $serializer);
        }
    }

    public function extract(SerializeEvent $event)
    {
        $subject = $event->getSubject();
        $data = &$event->getData();
        $serializer = $event->getSerializer();

        if ( ! is_object($subject)) {
            throw new InvalidArgumentException(sprintf(
                '$subject is expected to be an object, %s given',
                is_string($subject) ? $subject : gettype($subject)
            ));
        }

        foreach ($this->ancestry(get_class($subject)) as $parent) {
            $this->serializers->locate($parent)->serialize($subject, $data, $serializer);
        }
    }

    private function ancestry($subject)
    {
        $result = array($subject);
        while (false !== ($subject = get_parent_class($subject))) {
            $result[] = $subject;
        }
        return $result;
    }
}
