<?php

namespace Test\Drift\Types;

use Drift\Types\FloatType;

class FloatTypeTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return [
            ['0', 0],
            ['1', 1],
            [1, 1],
            [1.23, 1.23],
            ['2.3412', 2.3412],
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testExpectedConversion($input, $expected)
    {
        $this->assertEquals($expected, (new FloatType($input))->getValue());
    }
}
