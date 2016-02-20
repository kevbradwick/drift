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

    private $typeMap = [
        'Boolean' => ['bool', 'boolean'],
        'Int' => ['int', 'integer'],
        'String' => ['string', 'str'],
        'Array' => ['array', 'list'],
        'Date' => ['date',  'datetime'],
        'Float' => ['float']
    ];

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
     * @return array
     */
    public function getData()
    {
        return $this->data;
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
        $type = strtolower($config['type']);
        $class = null;

        foreach ($this->typeMap as $typeClass => $list) {
            if (in_array($type, $list)) {
                // check registered custom types
                if (class_exists($typeClass)) {
                    $class = $typeClass;
                    continue;
                }
                $class = '\Drift\Types\\' . $typeClass . 'Type';
            }
        }

        if ($class === null || !class_exists($class)) {
            throw new TypeException(sprintf(
                '"%s" is an unknown type specified for field "%s"',
                $type,
                $config['field']
            ));
        }

        /** @var AbstractType $type */
        $type = new $class($rawValue);
        $type->setMapper($this);

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

    /**
     * Register a new type class. This class be used to transform the raw
     * value into something else. The class must implement the TypeInterface.
     *
     * @param string $class the full class name that will perform the type
     * conversion.
     * @param string $name the name to give the type
     * @param array ...$names
     */
    public function registerType($class, $name, ...$names)
    {
        if (!class_exists($class)) {
            throw new TypeException(sprintf(
                'Cannot register type, class "%s" does not exist',
                $class
            ));
        }

        // check class implements type interface
        $interface = 'Drift\Types\TypeInterface';
        if (!in_array($interface, class_implements($class))) {
            throw new TypeException(sprintf(
                'The class "%s" does not implement "%s"',
                $class,
                $interface
            ));
        }

        $aliases = [];
        foreach ($this->typeMap as $k => $list) {
            $aliases = array_merge($aliases, $list);
        }

        array_unshift($names, $name);

        foreach ($names as $name_) {
            if (in_array($name_, $aliases)) {
                throw new TypeException(sprintf(
                    'The name "%s" is already registered',
                    $name_
                ));
            }
        }

        $this->typeMap[$class] = $names;
    }
}
