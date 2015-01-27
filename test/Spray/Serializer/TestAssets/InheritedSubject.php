<?php

namespace Spray\Serializer\TestAssets;

class InheritedSubject extends Subject
{
    /**
     * @var Subject
     */
    private $foobar;
    
    public function __construct($foo, $bar, $baz, Subject $foobar)
    {
        parent::__construct($foo, $bar, $baz);
        $this->foobar = $foobar;
    }
}
