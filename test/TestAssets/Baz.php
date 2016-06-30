<?php

namespace Spray\Serializer\TestAssets;

final class Baz extends Bar
{
    /**
     * @var array
     */
    private $arrays = array(
        'key' => 'value',
        'array' => array(
            'key' => 'value'
        )
    );
}
