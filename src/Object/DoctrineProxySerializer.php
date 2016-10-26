<?php

namespace Spray\Serializer\Object;

use Doctrine\Common\Persistence\Proxy;
use Spray\Serializer\SerializerInterface;

final class DoctrineProxySerializer implements SerializerInterface
{
    public function accepts($subject)
    {
        $implements = class_implements($subject);
        return isset($implements[Proxy::class]);
    }

    public function serialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {

    }

    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {

    }
}
