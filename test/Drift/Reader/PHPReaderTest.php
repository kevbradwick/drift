<?php

namespace Test\Drift\Reader;

use Drift\Reader\PHPReader;
use fixtures\Movie;

class PHPReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPReader
     */
    private $reader;

    public function setUp()
    {
        $file = realpath(__DIR__ . '/../../fixtures') . '/config.php';
        $this->reader = new PHPReader($file);
    }

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessage The file "foo" does not exist
     */
    public function testExceptionWhenFileNotExists()
    {
        new PHPReader('foo');
    }

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessage No properties defined for class "foo"
     */
    public function testGetPropertiesExceptionWhenClassNotDefined()
    {
        $this->reader->getProperties('foo');
    }

    public function testFieldNameIsPropertyNameByDefault()
    {
        $name = $this->reader->getProperties(Movie::class)['rating']['field'];
        $this->assertEquals('rating', $name);
    }
}
