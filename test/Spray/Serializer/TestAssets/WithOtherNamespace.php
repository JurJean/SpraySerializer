<?php

namespace Spray\Serializer\TestAssets;

use Spray\Serializer\TestAssets\OtherNamespace\InOtherNamespace;

class WithOtherNamespace
{
    /**
     * @var InOtherNamespace 
     */
    private $foo;
    
    /**
     * @param InOtherNamespace $foo
     */
    public function __construct(InOtherNamespace $foo)
    {
        $this->foo = $foo;
    }
}
