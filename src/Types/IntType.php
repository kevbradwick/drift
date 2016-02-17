<?php

namespace Drift\Types;

class IntType extends AbstractType
{
    /**
     * @return int
     */
    public function getValue()
    {
        return (int) $this->getOriginalValue();
    }
}
