<?php

namespace Spray\Serializer\Object;

interface ConstructorInterface
{
    public function construct($subject, &$data = array());
}
