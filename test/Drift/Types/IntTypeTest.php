<?php

namespace Test\Drift\Types;

use Drift\Types\IntType;

class IntTypeTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return [
            ['0', 0],
            ['1', 1],
            [1, 1]
        ];
    }

    /**
     * @param $input
     * @param $expected
     * @dataProvider dataProvider
     */
    public function testExpectedConversion($input, $expected)
    {
        $this->assertEquals($expected, (new IntType($input))->getValue());
    }
}
