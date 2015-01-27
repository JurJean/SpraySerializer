<?php

namespace Spray\Serializer;

use ReflectionClass;

class ReflectionRegistry implements ReflectionRegistryInterface
{
    private $reflections = array();
    
    public function getReflection($class)
    {
        if ( ! isset($this->reflections[$class])) {
            $this->reflections[$class] = new ReflectionClass($class);
        }
        return $this->reflections[$class];
    }
}
