<?php

namespace Spray\Serializer\Cache;

use RuntimeException;

class ArrayCache implements CacheInterface
{
    private $cached = array();
    
    public function exists($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return isset($this->cached[$subject]);
    }
    
    public function load($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        if ( ! $this->exists($subject)) {
            throw new RuntimeException(sprintf(
                'Subject %s has no cached serializer',
                $subject
            ));
        }
        $className = $subject . 'Serializer';
        if ( ! class_exists($className)) {
            eval('?>' . $this->cached[$subject]);
        }
        return new $className;
    }

    public function save($subject, $result)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        $this->cached[$subject] = $result;
    }
}
