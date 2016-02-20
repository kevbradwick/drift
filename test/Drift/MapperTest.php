<?php

namespace Test\Drift;

use fixtures\Car;
use fixtures\Movie;
use Drift\Mapper;
use Drift\Reader\AnnotationReader;

class MapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationReader|\PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    /**
     * @var Mapper
     */
    public $mapper;

    public function setUp()
    {
        $builder = $this->getMockBuilder(AnnotationReader::class);
        $builder->setMethods(['getProperties']);
        $this->reader = $builder->getMock();

        $this->mapper = new Mapper($this->reader);
    }

    public function testInstantiatingWithConstructorArgs()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn([]);

        /** @var Car $car */
        $car = $this->mapper->instantiate(Car::class, 'BMW', '318');

        $this->assertEquals('BMW', $car->getMake());
        $this->assertEquals('318', $car->getModel());
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessageRegExp /Unable to read the property "foo" for class/
     */
    public function testExceptionForUnknownProperty()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn(['foo' => []]);

        $this->mapper->instantiate(Movie::class);
    }

    public function testPropertyValueIsNotChangedIfThereISNoData()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn([]);

        /** @var Movie $movie */
        $movie = $this->mapper->instantiate(Movie::class);
        $this->assertEquals(120, $movie->getDuration());
    }

    public function testPropertyIsOverridden()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn(['duration' => [
                'field' => 'duration',
                'type' => 'int',
                'options' => [],
            ]]);

        $this->mapper->setData(['duration' => 90]);

        /** @var Movie $movie */
        $movie = $this->mapper->instantiate(Movie::class);
        $this->assertEquals(90, $movie->getDuration());
    }

    /**
     * @expectedException \Drift\Types\TypeException
     * @expectedExceptionMessage "foo" is an unknown type specified for field "duration"
     */
    public function testExceptionThrownForUnknownType()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn(['duration' => [
                'field' => 'duration',
                'type' => 'foo',
                'options' => [],
            ]]);

        $this->mapper->setData(['duration' => 90]);

        $this->mapper->instantiate(Movie::class);
    }

    /**
     * @expectedException \Drift\Types\TypeException
     * @expectedExceptionMessage Unknown config "foo" specified for type "int"
     */
    public function testExceptionForUnknownTypeOption()
    {
        $this->reader->expects($this->once())
            ->method('getProperties')
            ->willReturn(['duration' => [
                'field' => 'duration',
                'type' => 'int',
                'options' => [
                    'foo' => 'bar',
                ],
            ]]);

        $this->mapper->setData(['duration' => 90]);

        $this->mapper->instantiate(Movie::class);
    }

    public function testTypeOptionSetting()
    {

    }
}
