<?php

namespace Drift\Types;

class StringType extends AbstractType
{
    /**
     * @return string
     */
    public function getValue()
    {
        return (string) $this->getOriginalValue();
    }
}
