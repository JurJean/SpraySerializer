<?php

namespace Spray\Serializer;

use Spray\Serializer\Cache\CacheInterface;

class SerializerLocator implements SerializerLocatorInterface
{
    /**
     * @var SerializerRegistryInterface 
     */
    private $registry;
    
    /**
     * @var ObjectSerializerBuilderInterface 
     */
    private $builder;
    
    /**
     * @var CacheInterface
     */
    private $cache;
    
    public function __construct(
        SerializerRegistryInterface $registry,
        ObjectSerializerBuilderInterface $builder,
        CacheInterface $cache)
    {
        $this->registry = $registry;
        $this->builder = $builder;
        $this->cache = $cache;
    }
    
    public function locate($subject)
    {
        if ( ! $this->registry->contains($subject)) {
            if ( ! $this->cache->exists($subject)) {
                $this->cache->save($subject, $this->builder->build($subject));
            }
            $this->registry->add($this->cache->load($subject));
        }
        return $this->registry->find($subject);
    }
}
