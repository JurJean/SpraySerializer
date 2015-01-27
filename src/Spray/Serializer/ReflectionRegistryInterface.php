<?php

namespace Spray\Serializer;

use Zend\Code\Reflection\ReflectionInterface;

interface ReflectionRegistryInterface
{
    /**
     * @param string|object $class
     * @return ReflectionInterface
     */
    public function getReflection($class);
}
