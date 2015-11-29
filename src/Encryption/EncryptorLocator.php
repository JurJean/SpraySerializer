<?php

namespace Spray\Serializer\Encryption;

use Spray\Serializer\Cache\CacheInterface;

class EncryptorLocator implements EncryptorLocatorInterface
{
    /**
     * @var EncryptorGeneratorInterface
     */
    private $generator;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var array<EncryptorInterface>
     */
    private $located = array();

    public function __construct(
        EncryptorGeneratorInterface $generator,
        CacheInterface $cache)
    {
        $this->generator = $generator;
        $this->cache = $cache;
    }

    public function locate($subject)
    {
        if ( ! isset($this->located[$subject])) {
            if ( ! $this->cache->exists($subject)) {
                $this->cache->save($subject, $this->generator->generate($subject));
            }
            $this->located[$subject] = $this->cache->load($subject);
        }
        return $this->located[$subject];
    }
}
