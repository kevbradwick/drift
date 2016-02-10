<?php

namespace Test\Drift\Reader;

use Drift\Reader\AnnotationReader;
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
        $property = $this->reader->getProperties(Person::class)['firstName'];

        $this->assertEquals('String', $property['type']);
        $this->assertEmpty($property['spec']);
    }

    public function testSingleLineStatement()
    {
        $property = $this->reader->getProperties(Person::class)['age'];

        $this->assertEquals('Int', $property['type']);
        $this->assertEquals('age', $property['spec']['field']);
    }

    public function testMultiLineStatement()
    {
        $property = $this->reader->getProperties(Person::class)['mother'];

        $this->assertEquals('Entity', $property['type']);
        $this->assertEquals('parent_mother', $property['spec']['field']);
        $this->assertEquals(Person::class, $property['spec']['class']);
    }
}
