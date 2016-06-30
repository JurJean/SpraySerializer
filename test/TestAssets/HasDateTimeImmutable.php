<?php

namespace Spray\Serializer\TestAssets;

use DateTimeImmutable;

class HasDateTimeImmutable
{
    /**
     * @var DateTimeImmutable
     */
    public $dateTime;

    public function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }
}
