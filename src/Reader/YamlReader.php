<?php

namespace Drift\Reader;

class YamlReader extends AbstractReader
{
    private $config = [];

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

        $this->config = yaml_parse_file($fileName);
    }

    public function getProperties()
    {
        // TODO: Implement getProperties() method.
    }
}
