<?php

namespace Spray\Serializer;

use DateTime;

class DateTimeSerializer extends AbstractObjectSerializer
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
        parent::__construct('DateTime');
        $this->format = $format;
    }
    
    /**
     * {@inheritdoc}
     */
    public function construct($subject, &$data = array())
    {
        return DateTime::createFromFormat($this->format, $data);
    }
    
    protected function bindSerializer()
    {
        $format = $this->format;
        return function($subject, &$data, SerializerInterface $serializer) use ($format) {
            $data = $subject->format($format);
        };
    }

    protected function bindDeserializer()
    {
        return function($subject, &$data = array(), SerializerInterface $serializer = null) {
            return $subject;
        };
    }
}
