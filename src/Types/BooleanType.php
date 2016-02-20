<?php

namespace Drift\Types;

class BooleanType extends AbstractType
{
    /**
     * @return bool
     */
    public function getValue()
    {
        return boolval($this->getOriginalValue());
    }
}
