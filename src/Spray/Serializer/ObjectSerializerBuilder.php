<?php

namespace Spray\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;

class ObjectSerializerBuilder implements ObjectSerializerBuilderInterface
{
    /**
     * @var ReflectionRegistryInterface
     */
    private $reflections;
    
    /**
     * @var AnnotationReader
     */
    private $annotations;
    
    public function __construct(
        ReflectionRegistryInterface $reflections,
        AnnotationReader $annotations)
    {
        $this->reflections = $reflections;
        $this->annotations = $annotations;
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
            $type = $this->annotations->getPropertyAnnotation($property, 'var');
            $result[] = array(
                'name' => $property->getName(),
                'object' => class_exists($type),
            );
        }
        return $result;
    }
}
