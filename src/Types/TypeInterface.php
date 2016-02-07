<?php

namespace Drift\Types;

interface TypeInterface
{
    /**
     * Casts the value to the correct type.
     *
     * @return mixed
     */
    public function getValue();
}
