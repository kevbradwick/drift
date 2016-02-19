<?php

namespace Test\Drift\Reader;

use Drift\Reader\AnnotationReader;

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

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessage The class "foo" is not on the PHP include path
     */
    public function testExceptionForUnknownClass()
    {
        $this->reader->getProperties('foo');
    }
}
