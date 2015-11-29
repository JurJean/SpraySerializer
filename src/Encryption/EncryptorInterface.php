<?php

namespace Spray\Serializer\Encryption;

use Zend\Crypt\BlockCipher;

interface EncryptorInterface
{
    /**
     * Encrypt $data.
     *
     * @param mixed $data
     * @param BlockCipher $blockCipher
     *
     * @return mixed
     */
    public function encrypt(&$data, BlockCipher $blockCipher);

    /**
     * Decrypt $data.
     *
     * @param mixed $data
     * @param BlockCipher $blockCipher
     *
     * @return mixed
     */
    public function decrypt(&$data, BlockCipher $blockCipher);
}
