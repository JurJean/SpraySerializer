<?php

namespace Spray\Serializer\TestAssets;

class WithHashMap
{
    /**
     * @var string[]
     */
    private $stringsArray = [];

    /**
     * @var Bar[]
     */
    private $objectsArray = [];

    /**
     * @var array<string,string>
     */
    private $stringsHash = [];

    /**
     * @var array<string,Bar>
     */
    private $objectsHash = [];

    public function __construct()
    {
        $this->stringsArray = ['bar'];
        $this->objectsArray = [new Bar('bar')];
        $this->stringsHash = ['foo' => 'bar'];
        $this->objectsHash = ['foo' => new Bar('bar')];
    }
}
