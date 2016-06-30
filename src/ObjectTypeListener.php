<?php

namespace Spray\Serializer;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class ObjectTypeListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(SerializeEvent::CONSTRUCT, array($this, 'inject'), 1000);
        $this->listeners[] = $events->attach(SerializeEvent::DESTRUCT, array($this, 'detect'), 1000);
    }

    /**
     * @param SerializeEvent $event
     */
    public function inject(SerializeEvent $event)
    {
        $data = &$event->getData();

        if ( ! isset($data['__type'])) {
            return;
        }

        $event->setSubject($data['__type']);
    }

    /**
     * @param SerializeEvent $event
     */
    public function detect(SerializeEvent $event)
    {
        $subject = $event->getSubject();

        if ( ! is_object($subject)) {
            return;
        }

        $data = &$event->getData();
        $data['__type'] = get_class($event->getSubject());
    }
}
