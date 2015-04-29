<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace;
use Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace as Alias;
use Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace as Barr;

class WithOtherNamespace
{
    /**
     * @var InOtherNamespace 
     */
    private $foo;

    /**
     * @var Alias
     */
    private $bar;
    
    /**
     * @param InOtherNamespace $foo
     * @param Alias            $bar
     */
    public function __construct(InOtherNamespace $foo, Alias $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
