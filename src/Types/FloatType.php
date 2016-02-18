<?php

namespace Drift\Types;

class FloatType extends AbstractType
{
    /**
     * @return float
     */
    public function getValue()
    {
        return floatval($this->getOriginalValue());
    }
}
