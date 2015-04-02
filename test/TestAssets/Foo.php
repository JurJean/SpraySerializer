<?php

namespace Spray\Serializer\TestAssets;

use DateTime;

class Foo
{
    /**
     * @var BarCollection
     */
    private $bars;
    
    /**
     * @var Baz
     */
    private $baz;
    
    /**
     * @var DateTime
     */
    private $date;
    
    public function __construct(BarCollection $bars = null, Baz $baz = null, DateTime $date = null)
    {
        $this->bars = $bars;
        $this->baz = $baz;
        $this->date = $date;
    }
}
