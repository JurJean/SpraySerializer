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
    private $secretString = 'Foo';

    /**
     * @private
     * @var array
     */
    private $secretArray = ['Foo'];
}
