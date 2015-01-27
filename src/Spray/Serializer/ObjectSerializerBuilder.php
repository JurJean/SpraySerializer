<?php

namespace Spray\Serializer;

class ObjectSerializerBuilder implements ObjectSerializerBuilderInterface
{
    /**
     * @var ReflectionRegistryInterface
     */
    private $reflections;
    
    public function __construct(ReflectionRegistryInterface $reflections)
    {
        $this->reflections = $reflections;
    }
    
    public function build($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        
        $path = pathinfo(__FILE__);
        
        $parts = explode('\\', $subject);
        $class = array_pop($parts);
        $namespace = implode('\\', $parts);
        
        $properties = $this->findPropertiesDefinedInClass($subject);
        
        ob_start();
        include sprintf(
            '%s/ObjectSerializerBuilder.template',
            __DIR__
        );
        return ob_get_clean();
    }
    
    protected function findPropertiesDefinedInClass($subject)
    {
        $parent = get_parent_class($subject);
        if (false === $parent) {
            return $this->findProperties($subject);
        }
        return array_diff(
            $this->findProperties($subject),
            $this->findProperties($parent)
        );
    }
    
    protected function findProperties($subject)
    {
        $result = array();
        foreach ($this->reflections->getReflection($subject)->getProperties() as $property) {
            $result[] = $property->getName();
        }
        return $result;
    }
}
