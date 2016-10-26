<?php

namespace Spray\Serializer\Cache;

use RuntimeException;

class ArrayCache implements CacheInterface
{
    /**
     * @var
     */
    private $suffix;

    /**
     * @var array
     */
    private $cached = array();

    /**
     * @param $suffix
     */
    public function __construct($suffix)
    {
        $this->suffix = $suffix;
    }

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
                'Subject %s has no cached %s',
                $subject,
                $this->suffix
            ));
        }
        $className = $subject . $this->suffix;
        if ( ! class_exists($className, false)) {
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
