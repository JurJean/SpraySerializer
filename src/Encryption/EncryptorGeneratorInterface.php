<?php

namespace Spray\Serializer\Encryption;

interface EncryptorGeneratorInterface
{
    /**
     * Generate encryptor php code for given $className.
     *
     * @param string $className
     *
     * @return mixed
     */
    public function generate($className);
}
