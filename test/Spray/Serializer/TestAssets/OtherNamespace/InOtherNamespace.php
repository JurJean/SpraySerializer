<?php

namespace Spray\Serializer\TestAssets\OtherNamespace;

class InOtherNamespace
{
    /**
     * @var string
     */
    public $foo;
    
    /**
     * @param string $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
