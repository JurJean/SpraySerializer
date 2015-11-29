<?php

namespace Spray\Serializer\Encryption;

interface EncryptorLocatorInterface
{
    /**
     * @param string $subject
     *
     * @return EncryptorInterface
     */
    public function locate($subject);
}
