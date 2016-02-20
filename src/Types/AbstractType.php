<?php

namespace Drift\Types;

use Drift\Mapper;

abstract class AbstractType implements TypeInterface
{
    private $originalValue;

    /**
     * @var Mapper
     */
    private $mapper;

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

    public function setMapper(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @return Mapper
     */
    protected function getMapper()
    {
        return $this->mapper;
    }
}
