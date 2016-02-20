<?php

namespace Drift\Types;

use Drift\Mapper;

interface TypeInterface
{
    /**
     * Casts the value to the correct type.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * @param Mapper $mapper
     * @return mixed
     */
    public function setMapper(Mapper $mapper);
}
