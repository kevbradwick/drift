<?php

namespace Drift\Types;

class ArrayType extends AbstractType
{
    /**
     * @var string
     */
    private $delimiter = ',';

    /**
     * @var bool
     */
    private $autoConvert = false;

    /**
     * @param string $delimiter
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = (string) $delimiter;
    }

    /**
     * @param bool $convert
     */
    public function setAutoConvert($convert)
    {
        $this->autoConvert = (bool) $convert;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $original = $this->getOriginalValue();

        if (is_array($original)) {
            return $original;
        }

        if (!is_string($original)) {
            $this->typeError('Array');
        }

        $convert = $this->autoConvert;

        return array_map(function ($value) use ($convert) {
            $value = trim($value);
            if (!$convert) {
                return $value;
            }

            if (preg_match('/\d+\.\d+/', $value)) {
                return floatval($value);
            }

            if (preg_match('/\d+/', $value)) {
                return intval($value);
            }

            if (preg_match('/false|true/', $value)) {
                return $value === 'true';
            }

            if ($value === 'null') {
                return null;
            }

            return $value;
        }, explode($this->delimiter, $original));
    }
}
