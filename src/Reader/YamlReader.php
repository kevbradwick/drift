<?php

namespace Drift\Reader;

class YamlReader extends AbstractReader
{
    /**
     * @var array|mixed
     */
    private $config = [];

    /**
     * @var
     */
    private $fileName;

    /**
     * YamlReader constructor.
     * @param $fileName
     * @throws ReaderException if file not found or yaml extension not loaded
     */
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
        if (!array_key_exists($className, $this->config)) {
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

        if (!is_array($this->config[$className])) {
            $this->config[$className] = [];
        }

        $properties = [];
        foreach ($this->config[$className] as $name => $config) {
            $properties[$name] = array_replace($defaults, $config);
            // if no field name is present then use the name of the key used
            // in the yaml structure
            if (!$properties[$name]['field']) {
                $properties[$name]['field'] = $name;
            }
        }

        return $properties;
    }
}
