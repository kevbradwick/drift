<?php

namespace Drift\Reader;

class PHPReader extends AbstractReader
{
    /**
     * @var string
     */
    private $phpFile;

    /**
     * @var array
     */
    private $config = [];

    public function __construct($fileName)
    {
        if (!file_exists($fileName)) {
            throw new ReaderException(sprintf(
                'The file "%s" does not exist',
                $fileName
            ));
        }

        $this->config = include $fileName;
    }

    /**
     * @param $className
     * @return array
     * @throws ReaderException
     */
    public function getProperties($className)
    {
        if (!array_key_exists($className, $this->config)) {
            throw new ReaderException(sprintf(
                'No properties defined for class "%s"',
                $className
            ));
        }

        $properties = [];

        if (isset($this->config[$className])) {
            $properties = $this->config[$className];
        }

        foreach ($properties as $name => $config) {
            $properties[$name] = array_replace($this->defaults(), $config);
            if (!$properties[$name]['field']) {
                $properties[$name]['field'] = $name;
            }
        }

        return $properties;
    }
}
