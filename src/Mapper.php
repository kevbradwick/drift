<?php

namespace Drift;

use Drift\Reader\AbstractReader;
use Drift\Types\DateType;
use Drift\Types\StringType;

class Mapper
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $className;

    /**
     * @var AbstractReader
     */
    private $reader;

    public function __construct(AbstractReader $reader, array $data = [])
    {
        $this->reader = $reader;
        $this->data = $data;
    }

    /**
     * @param string $className the class to instantiate
     * @param array ...$args any number of constructor arguments
     * @return mixed
     */
    public function instantiate($className, ...$args)
    {
        $class = new \ReflectionClass($className);
        $instance = $class->newInstanceArgs($args);

        foreach ($this->reader->getProperties($className) as $name => $config) {
            try {
                $property = $class->getProperty($name);
            } catch (\ReflectionException $e) {
                throw new \RuntimeException(sprintf(
                    'Unable to read the property "%s" for class "%s"',
                    $name,
                    $className
                ));
            }

            $field = $config['field'] ? $config['field'] : $name;

            if (!isset($this->data[$field])) {
                continue;
            }

            $originalValue = $this->data[$field];
            $newValue = $originalValue;

            switch ($config['type']) {
                case 'string':
                    $newValue = (new StringType($originalValue))->getValue();
                    break;
                case 'date':
                    $newValue = (new DateType($originalValue))->getValue();
                    break;
            }

            $property->setAccessible(true);
            $property->setValue($instance, $newValue);
        }

        return $instance;
    }
}
