<?php

namespace Drift\Reader;

class YamlReader extends AbstractReader
{
    private $config = [];

    private $fileName;

    public function __construct($fileName)
    {
        if (!file_exists($fileName)) {
            throw new ReaderException(sprintf(
                'The file "%s" does not exist',
                $fileName
            ));
        }

        if (!function_exists('yaml_parse_file')) {
            throw new ReaderException(sprintf(
                'The PECL Yaml extension needs to be installed in order to read the file'
            ));
        }

        $this->fileName = $fileName;
        $this->config = yaml_parse_file($fileName);
    }

    public function getProperties($className)
    {
        if (!isset($this->config[$className])) {
            throw new ReaderException(sprintf(
                'The class "%s" is not defined in %s',
                $className,
                $this->fileName
            ));
        }

        $defaults = [
            'type' => null,
            'field' => null,
            'options' => [],
        ];

        $config = array_map(function ($property) use ($defaults) {
            return array_replace($defaults, $property);
        }, $this->config[$className]);

        return $config;
    }
}
