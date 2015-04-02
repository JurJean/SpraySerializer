<?php

namespace Spray\Serializer\Cache;

interface CacheInterface
{
    public function exists($subject);
    
    public function save($subject, $result);
    
    public function load($subject);
}
