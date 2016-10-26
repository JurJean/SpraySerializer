<?php

namespace Spray\Serializer\Object;

use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\EntityNotFoundException;
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
        try {
            $subject->__load();
        } catch (EntityNotFoundException $error) {
            $data = [];
        }
    }

    public function deserialize($subject, &$data = array(), SerializerInterface $serializer = null)
    {

    }
}
