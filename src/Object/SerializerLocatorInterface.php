<?php

namespace Spray\Serializer\Object;

use Spray\Serializer\SerializerInterface;

interface SerializerLocatorInterface
{
    /**
     * @param string $subject
     *
     * @return SerializerInterface
     */
    public function locate($subject);
}
