<?php

namespace Test\Drift\Reader;

use Drift\Reader\AnnotationReader;
use fixtures\Movie;

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

    public function testTypeIsCorrect()
    {
        $type = $this->reader->getProperties(Movie::class)['title']['type'];
        $this->assertEquals('string', $type);
    }

    public function testFieldIsSameAsPropertyNameWhenNotPartOfSpec()
    {
        $field = $this->reader->getProperties(Movie::class)['title']['field'];
        $this->assertEquals('title', $field);
    }

    public function testFieldNameIsOverridden()
    {
        $spec = $this->reader->getProperties(Movie::class)['releaseDate'];
        $this->assertEquals('release_date', $spec['field']);
    }

    public function testSpecForMultilineComment()
    {
        $spec = $this->reader->getProperties(Movie::class)['releaseDate'];
        $this->assertEquals('Y-m-d', $spec['options']['format']);
    }

    public function testSpecForSingleLineComment()
    {
        $spec = $this->reader->getProperties(Movie::class)['rating'];

        $this->assertEquals('film_rating', $spec['field']);
        $this->assertEquals('-', $spec['options']['delimiter']);
    }
}
