<?php

namespace Spray\Serializer;

interface SerializerLocatorInterface
{
    /**
     * @param string $subject
     *
     * @return SerializerInterface
     */
    public function locate($subject);
}
