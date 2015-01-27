<?php

namespace Spray\Serializer;

use ReflectionClass;

interface ReflectionRegistryInterface
{
    /**
     * @param string|object $class
     * @return ReflectionClass
     */
    public function getReflection($class);
}
