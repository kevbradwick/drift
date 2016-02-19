<?php

namespace Drift;

use Drift\Reader\AbstractReader;
use Drift\Types\AbstractType;
use Drift\Types\DateType;
use Drift\Types\StringType;
use Drift\Types\TypeException;

class Mapper
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var AbstractReader
     */
    private $reader;

    public function __construct(AbstractReader $reader, array $data = [])
    {
        $this->reader = $reader;
        $this->setData($data);
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
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
            $newValue = $this->createTypeClass($config, $originalValue)->getValue();

            $property->setAccessible(true);
            $property->setValue($instance, $newValue);
        }

        return $instance;
    }

    /**
     * @param array $config
     * @param mixed $rawValue
     * @return AbstractType
     */
    private function createTypeClass(array $config, $rawValue)
    {
        $class = '\Drift\Types\\' . ucfirst($config['type']) . 'Type';

        if (!class_exists($class)) {
            throw new TypeException(sprintf(
                '"%s" is an unknown type specified for field "%s"',
                $config['type'],
                $config['field']
            ));
        }

        $type = new $class($rawValue);

        foreach ($config['options'] as $name => $value) {
            $methodName = 'set' . ucfirst($name);
            if (!method_exists($type, $methodName)) {
                throw new TypeException(sprintf(
                    'Unknown config "%s" specified for type "%s"',
                    $name,
                    $config['type']
                ));
            }

            $type->{$methodName}($value);
        }

        return $type;
    }
}
