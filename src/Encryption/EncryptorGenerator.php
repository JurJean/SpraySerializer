<?php

namespace Spray\Serializer\Encryption;

use Spray\Serializer\PropertyInfoInterface;

class EncryptorGenerator implements EncryptorGeneratorInterface
{
    /**
     * @var PropertyInfoInterface
     */
    private $propertyInfo;

    /**
     * @param PropertyInfoInterface $propertyInfo
     */
    public function __construct(
        PropertyInfoInterface $propertyInfo)
    {
        $this->propertyInfo = $propertyInfo;
    }

    public function generate($className)
    {
        ob_start();
        include sprintf(
            '%s/Encryptor.template',
            __DIR__
        );
        return ob_get_clean();
    }
}
