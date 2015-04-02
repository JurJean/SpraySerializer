<?php

namespace Spray\Serializer\TestAssets;

use DateTime;

class InheritedSubject extends Subject
{
    /**
     * @var Subject
     */
    private $foobar;
    
    /**
     * @var DateTime
     */
    private $barbaz;
    
    public function __construct($foo, $bar, $baz, Subject $foobar, DateTime $barbaz)
    {
        parent::__construct($foo, $bar, $baz);
        $this->foobar = $foobar;
        $this->barbaz = $barbaz;
    }
}
