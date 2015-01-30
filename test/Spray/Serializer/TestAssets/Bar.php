<?php

namespace Spray\Serializer\TestAssets;

class Bar
{
    private $foobar;
    
    public function __construct($foobar)
    {
        $this->foobar = (string) $foobar;
    }
}
