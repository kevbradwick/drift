<?php

namespace fixtures;

class Car
{
    /**
     * @var string
     */
    private $make;

    /**
     * @var string
     */
    private $model;

    /**
     * @Drift\String()
     * @var string
     */
    private $colour;

    public function __construct($make, $model)
    {
        $this->make = $make;
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }
}
