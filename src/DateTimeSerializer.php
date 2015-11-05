<?php

namespace Spray\Serializer;

use DateTime;
use DateTimeImmutable;

class DateTimeSerializer implements SerializerInterface, ConstructorInterface
{
    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct($format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function accepts($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return $subject === 'DateTime';
    }

    /**
     * {@inheritdoc}
     */
    public function construct($subject, &$data = array())
    {
        return DateTime::createFromFormat($this->format, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        $data = $subject->format($this->format);
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {
        return $subject;
    }
}
