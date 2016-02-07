<?php

namespace Drift\Types;

abstract class AbstractType implements TypeInterface
{
    private $originalValue;

    public function __construct($value)
    {
        $this->originalValue = $value;
    }

    protected function getOrginalValue()
    {
        return $this->originalValue;
    }
}
