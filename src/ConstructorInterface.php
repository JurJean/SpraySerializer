<?php

namespace Spray\Serializer;

interface ConstructorInterface
{
    public function construct($subject, &$data = array());
}
