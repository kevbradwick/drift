<?php

namespace Test\Drift\Types;

use Drift\Types\StringType;

class StringTypeTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        return [
            [123, '123'],
            [null, ''],
            [true, '1'],
            [false, ''],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testGetValueForCommonTypes($original, $expected)
    {
        $type = new StringType($original);
        $this->assertSame($expected, $type->getValue());
    }
}
