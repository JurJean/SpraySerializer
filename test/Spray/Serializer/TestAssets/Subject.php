<?php

namespace Spray\Serializer\TestAssets;

class Subject
{
    private $foo;
    
    protected $bar;
    
    public $baz;
    
    public function __construct($foo, $bar, $baz)
    {
        $this->foo = $foo;
        $this->bar = $bar;
        $this->baz = $baz;
    }
}
