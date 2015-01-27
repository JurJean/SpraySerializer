<?php

namespace Spray\Serializer\TestAssets;

class InheritedSubject extends Subject
{
    private $foobar;
    
    public function __construct($foo, $bar, $baz, $foobar)
    {
        parent::__construct($foo, $bar, $baz);
        $this->foobar = $foobar;
    }
}
