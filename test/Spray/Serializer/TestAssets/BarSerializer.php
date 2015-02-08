<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\AbstractObjectSerializer;
use Spray\Serializer\SerializerInterface;

class BarSerializer extends AbstractObjectSerializer
{
    public function __construct()
    {
        parent::__construct(
            function($subject, array &$data, SerializerInterface $serializer) {
                $data['foobar'] = $subject->foobar;
            },
            function($subject, array &$data, SerializerInterface $serializer) {
                $subject->foobar = $data['foobar'];
            },
            'Spray\Serializer\TestAssets\Bar'
        );
    }
}
