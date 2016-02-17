<?php

namespace Test\Drift\Types;

use Drift\Types\ArrayType;

class ArrayTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testReturningArrayWithNoCoversion()
    {
        $value = [1, 2, 3];

        $this->assertSame($value, (new ArrayType($value))->getValue());
    }

    public function testStringToArrayConversion()
    {
        $value = ['foo', 'bar', '123'];
        $type = new ArrayType('foo, bar, 123');

        $this->assertEquals($value, $type->getValue());
    }

    public function testCustomDelimiter()
    {
        $value = ['foo', '123'];
        $type = new ArrayType('foo|123');
        $type->setDelimiter('|');

        $this->assertEquals($value, $type->getValue());
    }

    public function testAutocastVariables()
    {
        $value = [123, 32.1, false, true, null, 'foo'];
        $type = new ArrayType('123, 32.1, false, true, null, foo');
        $type->setAutoConvert(true);

        $this->assertSame($value, $type->getValue());
    }
}
