<?php

namespace Drift\Reader;

abstract class AbstractReader implements ReaderInterface
{
    protected function defaults()
    {
        return [
            'type' => null,
            'field' => null,
            'options' => [],
        ];
    }
}
