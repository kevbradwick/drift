<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Drift\Mapper;
use Drift\Reader\AnnotationReader;

class Car
{

    /**
     * @Drift\String(field="0.make")
     */
    private $make;

    /**
     * @Drift\String(field="0.model")
     */
    private $model;

    public function __toString()
    {
        return 'Car(make=' . $this->make . ', model=' . $this->model . ')';
    }
}

$mapper = new Mapper(new AnnotationReader());
$mapper->enableDotNotation(true);
$mapper->setData([
    ['make' => 'Ferrari', 'model' => '335']
]);

$car = $mapper->instantiate(Car::class);
echo $car;
