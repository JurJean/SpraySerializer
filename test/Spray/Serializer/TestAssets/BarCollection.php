<?php

namespace Spray\Serializer\TestAssets;

/**
 * BarCollection
 */
class BarCollection
{
    /**
     * @var array<Bar>
     */
    private $items = array();
    
    public function __construct(array $items)
    {
        $this->items = $items;
    }
}
