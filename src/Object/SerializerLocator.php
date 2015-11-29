<?php

namespace Spray\Serializer\Object;

use Spray\Serializer\Cache\CacheInterface;
use Spray\Serializer\Object\ObjectSerializerGeneratorInterface;
use Spray\Serializer\Object\SerializerLocatorInterface;
use Spray\Serializer\Object\SerializerRegistryInterface;

class SerializerLocator implements SerializerLocatorInterface
{
    /**
     * @var SerializerRegistryInterface 
     */
    private $registry;
    
    /**
     * @var ObjectSerializerGeneratorInterface
     */
    private $generator;
    
    /**
     * @var CacheInterface
     */
    private $cache;
    
    /**
     * @var array<SerializerInterface>
     */
    private $located = array();
    
    public function __construct(
        SerializerRegistryInterface $registry,
        ObjectSerializerGeneratorInterface $generator,
        CacheInterface $cache)
    {
        $this->registry = $registry;
        $this->generator = $generator;
        $this->cache = $cache;
    }
    
    public function locate($subject)
    {
        if ( ! isset($this->located[$subject])) {
            if ( ! $this->registry->contains($subject)) {
                if ( ! $this->cache->exists($subject)) {
                    $this->cache->save($subject, $this->generator->generate($subject));
                }
                $this->registry->add($this->cache->load($subject));
            }
            $this->located[$subject] = $this->registry->find($subject);
        }
        return $this->located[$subject];
    }
}
