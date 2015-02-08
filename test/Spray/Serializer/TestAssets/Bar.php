<?php

namespace Spray\Serializer\TestAssets;

class Bar
{
    /**
     * @var string
     */
    private $foobar;
    
    public function __construct($foobar)
    {
        $this->foobar = (string) $foobar;
    }
}
