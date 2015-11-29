<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\Encryption\EncryptorInterface;
use Zend\Crypt\BlockCipher;

class PrivateStuffEncryptor implements EncryptorInterface
{
    public function encrypt(&$data, BlockCipher $blockCipher)
    {
        $data['secretString'] = $blockCipher->encrypt($data['secretString']);
        $data['secretArray'] = $blockCipher->encrypt(json_encode($data['secretArray']));
    }

    public function decrypt(&$data, BlockCipher $blockCipher)
    {
        $data['secretString'] = $blockCipher->decrypt($data['secretString']);
        $data['secretArray'] = json_decode($blockCipher->decrypt($data['secretArray']), true);
    }
}
