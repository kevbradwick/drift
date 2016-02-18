<?php

namespace Test\Drift\Reader;

use Drift\Reader\YamlReader;

class YamlReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var YamlReader
     */
    private $reader;

    public function setUp()
    {
        $configFile = realpath(__DIR__ . '/../../fixtures') . '/config.yml';
        $this->reader = new YamlReader($configFile);
    }

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessage The file "foo.yml" does not exist
     */
    public function testExceptionThrownWhenFileNotFound()
    {
        new YamlReader('foo.yml');
    }

    /**
     * @expectedException \Drift\Reader\ReaderException
     * @expectedExceptionMessageRegExp /^The class "foo" is not defined/
     */
    public function testExceptionThrownForUnknownClass()
    {
        $this->reader->getProperties('foo');
    }

    public function testDefaultPropertiesAppliedToPartialConfig()
    {
        $properties = $this->reader->getProperties('fixtures\Movie')['rating'];

        $this->assertArrayHasKey('type', $properties);
        $this->assertArrayHasKey('field', $properties);
        $this->assertArrayHasKey('options', $properties);
    }

    public function testFieldValueIsPropertyNameIfNotOverriden()
    {
        $properties = $this->reader->getProperties('fixtures\Movie')['rating'];
        $this->assertSame('rating', $properties['field']);
    }

    public function testFieldNameIsOverriden()
    {
        $properties = $this->reader->getProperties('fixtures\Movie')['title'];
        $this->assertSame('film_title', $properties['field']);
    }
}
