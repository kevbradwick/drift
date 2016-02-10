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
                '/
                Drift                   # Drift namespace
                \\\                     # backslash, escaped
                (?P<type>[a-zA-Z]+)     # the type class
                \(                      # open parens (always)
                (?P<spec>.+?)?          # optional type specification
                \)                      # closing parens (always)
                /xsm',
                $property->getDocComment(),
                $matches
            );

            if (isset($matches['type'])) {
                $prop = [
                    'type' => $matches['type'],
                    'spec' => [],
                ];
                if (isset($matches['spec'])) {
                    $prop['spec'] = $this->parseSpec($matches['spec']);
                }
                $properties[$property->getName()] = $prop;
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

        preg_match_all(
            '/(?P<name>\w+)="?(?P<value>[\w\\\\-]+)"?/',
            $specString,
            $matches,
            \PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $spec[$match['name']] = $match['value'];
        }

        return $spec;
    }
}
