<?php

namespace Spray\Serializer\Cache;

use Symfony\Component\Filesystem\Filesystem;

class FileCache implements CacheInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    private $path;
    
    public function __construct(Filesystem $filesystem, $path)
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
    }
    
    public function exists($subject)
    {
        return $this->filesystem->exists($this->normalizePath($subject));
    }

    public function load($subject)
    {
        if ( ! $this->exists($subject)) {
            throw new RuntimeException(
                'Cannot load serializer from filesystem, %s does not exist',
                $this->normalizePath($subject)
            );
        }
        require_once($this->normalizePath($subject));
        return new $subject;
    }

    public function save($subject, $result)
    {
        $this->filesystem->dumpFile($this->normalizePath($subject), $result);
    }
    
    protected function normalizePath($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return sprintf(
            '%s/%s.php',
            $this->path,
            str_replace('\\', '_', $subject)
        );
    }
}
