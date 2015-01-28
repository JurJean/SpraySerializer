<?php

namespace Spray\Serializer;

use DateTime;

/**
 * DateTimeSerializer
 */
class DateTimeSerializer extends AbstractObjectSerializer
{
    private $format;
    
    public function __construct($format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) use ($format) {
                $data = $subject->format($format);
            },
            function($subject, &$data, SerializerInterface $serializer) use ($format) {
                return $subject;
//                return DateTime::createFromFormat($format, $subject);
            },
            'DateTime'
        );
    }
    
    public function construct($subject, &$data = array())
    {
        return DateTime::createFromFormat($this->format, $data);
    }
    
    
}
