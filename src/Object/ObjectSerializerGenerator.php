<?php

namespace Spray\Serializer\Object;

use Spray\Serializer\PropertyInfoInterface;

class ObjectSerializerGenerator implements ObjectSerializerGeneratorInterface
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

    /**
     * @param string $className
     *
     * @return string
     */
    public function generate($className)
    {
        ob_start();
        include sprintf(
            '%s/ObjectSerializer.template',
            __DIR__
        );
        return ob_get_clean();
    }
}
