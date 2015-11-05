<?php

namespace Spray\Serializer;

use Zend\EventManager\Event;

final class SerializeEvent extends Event
{
    const CONSTRUCT = 'construct';
    const DESTRUCT  = 'destruct';
    const INJECT    = 'inject';
    const EXTRACT   = 'extract';

    /**
     * @var null|string|object
     */
    private $subject;

    /**
     * @var array
     */
    private $data;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param null|string|object $subject
     * @param array $data
     * @param SerializerInterface $serializer
     */
    public function __construct($subject, &$data, SerializerInterface $serializer)
    {
        $this->subject = $subject;
        $this->data = &$data;
        $this->serializer = $serializer;

        parent::__construct();
    }

    /**
     * @param null|string|object $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return null|object|string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function &getData()
    {
        return $this->data;
    }

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        return $this->serializer;
    }
}
