<?php

namespace Spray\Serializer;

use DateTimeImmutable;

class DateTimeImmutableSerializer extends AbstractObjectSerializer
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
        parent::__construct('DateTimeImmutable');
        $this->format = $format;
    }
    
    /**
     * {@inheritdoc}
     */
    public function construct($subject, &$data = array())
    {
        return DateTimeImmutable::createFromFormat($this->format, $data);
    }
    
    /**
     * {@inheritdoc}
     */
    protected function bindSerializer()
    {
        $format = $this->format;
        return function($subject, &$data, SerializerInterface $serializer) use ($format) {
            $data = $subject->format($format);
        };
    }

    /**
     * {@inheritdoc}
     */
    protected function bindDeserializer()
    {
        return function($subject, &$data, SerializerInterface $serializer) {
            return $subject;
        };
    }

}
