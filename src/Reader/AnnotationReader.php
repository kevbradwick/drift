<?php

namespace Drift\Reader;

class AnnotationReader extends AbstractReader
{
    /**
     * @param string $className
     * @return array
     * @throws ReaderException when the class does not exist
     */
    public function getProperties($className)
    {
        if (!class_exists($className)) {
            throw new ReaderException(sprintf(
                'The class %s does not exist',
                $className
            ));
        }

        $class = new \ReflectionClass($className);
        $properties = [];

        foreach ($class->getProperties() as $property) {
            preg_match(
                '/Drift\\\(?P<type>\w+)\((?P<spec>.*?)\)/',
                $property->getDocComment(),
                $matches
            );

            if (isset($matches['type'])) {
                $spec = [];
                if (isset($matches['spec'])) {
                    $spec = $this->parseSpec($matches['spec']);
                }
                $properties[$property->getName()] = [
                    'type' => strtolower($matches['type']),
                    'field' => isset($spec['field']) ? $spec['field'] : null,
                    'options' => isset($spec['options']) ? $spec['options'] : [],
                ];
            }
        }

        return $properties;
    }

    /**
     * @param string $specString
     * @return array
     */
    private function parseSpec($specString)
    {
        $spec = [];

        return $spec;
    }
}
