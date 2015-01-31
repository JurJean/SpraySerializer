<?php

namespace Spray\Serializer\TestAssets;

class Baz extends Bar
{
    private $arrays = array(
        'key' => 'value',
        'array' => array(
            'key' => 'value'
        )
    );
}
