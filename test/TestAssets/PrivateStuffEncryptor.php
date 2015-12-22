<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Encryption\EncryptorInterface;
use Zend\Crypt\BlockCipher;

class PrivateStuffEncryptor implements EncryptorInterface
{
    public function encrypt(&$data, BlockCipher $blockCipher)
    {
        $data['secretString'] = !empty($data['secretString']) ? $blockCipher->encrypt($data['secretString']) : null;
        $data['secretArray'] = !empty($data['secretArray'][0]) ? $blockCipher->encrypt(json_encode($data['secretArray'])) : null;
    }

    public function decrypt(&$data, BlockCipher $blockCipher)
    {
        $data['secretString'] = !empty($data['secretString']) ? $blockCipher->decrypt($data['secretString']) : null;
        $data['secretArray'] = !empty($data['secretArray']) ? json_decode($blockCipher->decrypt($data['secretArray']), true) : null;
    }
}
