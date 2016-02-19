<?php

namespace Drift\Reader;

class AnnotationReader extends AbstractReader
{
    public function getProperties($className)
    {
        $properties = [];

        if (!class_exists($className)) {
            throw new ReaderException(sprintf(
                'The class "%s" is not on the PHP include path',
                $className
            ));
        }

        $class = new \ReflectionClass($className);
        foreach ($class->getProperties() as $property) {
            $m = preg_match('/
                @Drift\\\
                (?P<type>[A-Z][a-zA-Z]+)
                \(
                (?P<spec>.+?)?
                \)
            /xms', $property->getDocComment(), $matches);

            if (!$m) {
                continue;
            }

            $config = [
                'type' => strtolower($matches['type']),
                'field' => null,
                'options' => [],
            ];

            if (isset($matches['spec'])) {
                $spec = $this->parseSpec($matches['spec']);
                if (isset($spec['field'])) {
                    $config['field'] = $spec['field'];
                    unset($spec['field']);
                }
                $config['options'] = $spec;
            }

            if (!$config['field']) {
                $config['field'] = $property->getName();
            }

            $properties[$property->getName()] = $config;
        }

        return $properties;
    }

    /**
     * Parse the key value spec into a flat array.
     *
     * @param string $spec
     * @return array
     */
    private function parseSpec($spec)
    {
        $pattern = '/(?P<key>\w+)="(?P<value>.+?)"/sm';
        $spec_ = [];

        if (preg_match_all($pattern, $spec, $matches, \PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $spec_[$match['key']] = $match['value'];
            }
        }

        return $spec_;
    }
}
