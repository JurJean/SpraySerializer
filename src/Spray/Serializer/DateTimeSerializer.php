<?php

namespace Spray\Serializer;

/**
 * DateTimeSerializer
 */
class DateTimeSerializer extends AbstractObjectSerializer
{
    public function __construct($format = 'Y-m-d H:i:s')
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) use ($format) {
                return $subject->format($format);
            },
            function($subject, &$data, SerializerInterface $serializer) use ($format) {
                return DateTime::createFromFormat($format, $subject);
            },
            'DateTime'
        );
    }
}
