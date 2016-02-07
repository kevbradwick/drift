<?php

namespace Drift;

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

    public function __construct($className, array $data = [])
    {
        if (!class_exists($className)) {
            throw new \InvalidArgumentException(sprintf(
                'The class "%s" does not exist or is not on the include path',
                $className
            ));
        }

        $this->className = $className;
        $this->data = $data;
    }

    /**
     * @param array ...$args any number of constructor arguments
     * @return mixed
     */
    public function instantiate(...$args)
    {
        $class = new \ReflectionClass($this->className);
        $instance = $class->newInstanceArgs($args);
        $reflect = new \ReflectionClass($instance);

        foreach ($reflect->getProperties() as $property) {
            if ($property->isPublic() === false) {
                $property->setAccessible(true);
            }

            $comment = $property->getDocComment();
            preg_match(
                '/Drift(?P<type>[a-z]+)\((?P<spec>.+?)?\)/i',
                $comment,
                $matches
            );

            // the type transformer class
            $type = isset($matches['type']) ? $matches['type'] : null;
            $properties = [];

            if (isset($matches['spec'])) {
                $parts = explode(' ', $matches['spec']);
                foreach ($parts as $part) {
                    var_dump($part);
                    preg_match(
                        '/^(?P<name>[a-z]+)\="?(?P<value>[a-z_\-]+)"?/i',
                        trim($part),
                        $matches
                    );
                    if (isset($matches['name']) && $matches['value']) {
                        $properties[$matches['name']] = $matches['value'];
                    }
                }
            }
        }

        return $instance;
    }
}
