<?php

namespace Spray\Serializer\TestAssets;

final class Baz extends Bar
{
    private $arrays = array(
        'key' => 'value',
        'array' => array(
            'key' => 'value'
        )
    );
}
