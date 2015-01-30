<?php

namespace Spray\Serializer\TestAssets;

use DateTime;

class Foo
{
    /**
     * @var array<Bar>
     */
    private $bars = array();
    
    /**
     * @var Baz
     */
    private $baz;
    
    /**
     * @var DateTime
     */
    private $date;
    
    public function __construct(array $bars, Baz $baz, DateTime $date = null)
    {
        $this->bars = $bars;
        $this->baz = $baz;
        $this->date = $date;
    }
}
