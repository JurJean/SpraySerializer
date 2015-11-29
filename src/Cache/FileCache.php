<?php

namespace Spray\Serializer\Cache;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class FileCache implements CacheInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $suffix;

    /**
     * @param Filesystem $filesystem
     * @param string $path
     * @param string $suffix
     */
    public function __construct(Filesystem $filesystem, $path, $suffix)
    {
        $this->filesystem = $filesystem;
        $this->path = $path;
        $this->suffix = $suffix;
    }
    
    public function exists($subject)
    {
        return $this->filesystem->exists($this->normalizePath($subject));
    }

    public function load($subject)
    {
        if ( ! $this->exists($subject)) {
            throw new RuntimeException(
                'Cannot load %s from filesystem, %s does not exist',
                $this->suffix,
                $this->normalizePath($subject)
            );
        }
        $class = $this->normalizeClass($subject);
        require_once($this->normalizePath($subject));
        return new $class;
    }

    public function save($subject, $result)
    {
        $this->filesystem->dumpFile($this->normalizePath($subject), $result);
    }
    
    protected function normalizeClass($subject)
    {
        if (is_object($subject)) {
            $subject = get_class($subject);
        }
        return $subject . $this->suffix;
    }
    
    protected function normalizePath($subject)
    {
        return sprintf(
            '%s/%s.php',
            $this->path,
            str_replace('\\', '_', $this->normalizeClass($subject))
        );
    }
}
