<?php

namespace Drift\Types;

abstract class AbstractType implements TypeInterface
{
    private $originalValue;

    public function __construct($value)
    {
        $this->originalValue = $value;
    }

    protected function getOriginalValue()
    {
        return $this->originalValue;
    }

    protected function typeError($type)
    {
        throw new TypeException(sprintf(
            'Unable to convert type "%s" into %s',
            gettype($this->getOriginalValue()),
            $type
        ));
    }
}
