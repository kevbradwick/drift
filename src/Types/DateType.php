<?php

namespace Drift\Types;

class DateType extends AbstractType
{
    /**
     * @var string
     */
    private $format = '';

    /**
     * @return \DateTime
     */
    public function getValue()
    {
        $original = $this->getOrginalValue();

        if (strlen($this->format) > 0) {
            return \DateTime::createFromFormat($this->format, $original);
        }

        if (is_int($original) || is_numeric($original)) {
            $original  = '@' . (string) $original;
        }

        return new \DateTime($original);
    }

    /**
     * Set the date format to be used when converting the date from a string.
     *
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = (string) $format;
    }
}
