<?php

namespace fixtures;

use Drift\Types\AbstractType;

class CustomType2 extends AbstractType
{
    public function getValue()
    {
        return strtoupper($this->getOriginalValue());
    }
}
