<?php

namespace Spray\Serializer\TestAssets;

class PrivateStuff
{
    /**
     * @var string
     */
    private $noSecret = 'Foo';

    /**
     * @private
     * @var string
     */
    private $secretString;

    /**
     * @private
     * @var array
     */
    private $secretArray;

    /**
     * @param string $noSecret
     * @param string $secretString
     * @param array $secretArray
     */
    public function __construct($noSecret = 'Foo', $secretString = 'Foo', $secretArray = ['Foo'])
    {
        $this->noSecret = (string) $noSecret;
        $this->secretString = (string) $secretString;
        $this->secretArray = (array) $secretArray;
    }
}
