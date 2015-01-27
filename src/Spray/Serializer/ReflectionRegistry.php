<?php

namespace Spray\Serializer;

use Zend\Code\Reflection\ClassReflection;

class ReflectionRegistry implements ReflectionRegistryInterface
{
    private $reflections = array();
    
    public function getReflection($class)
    {
        if ( ! isset($this->reflections[$class])) {
            $this->reflections[$class] = new ClassReflection($class);
        }
        return $this->reflections[$class];
    }
}
