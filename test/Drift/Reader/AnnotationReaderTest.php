<?php

namespace Test\Drift\Reader;

use Drift\Reader\AnnotationReader;
use Drift\Reader\ReaderException;
use fixtures\Person;

class AnnotationReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AnnotationReader
     */
    private $reader;

    public function setUp()
    {
        $this->reader = new AnnotationReader();
    }

    public function testPropertiesWithoutAnnotationsAreIgnored()
    {
        $properties = $this->reader->getProperties(Person::class);
        $this->assertArrayNotHasKey('eyeColour', $properties);
    }

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessageRegExp /The class [\w\\]+ does not exist/
     */
    public function testExceptionIsThrownIfClassDoesNotExist()
    {
        $this->reader->getProperties('foo');
    }

    public function testBasicProperty()
    {
        $config = $this->reader->getProperties(Person::class)['firstName'];

        $this->assertEquals('string', $config['type']);
//        $this->assertEquals('firstName', $config['field']);
    }
}
