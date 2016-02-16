<?php

namespace Test\Drift\Types;

use Drift\Types\DateType;
use Drift\Types\TypeException;

class DateTypeTest extends \PHPUnit_Framework_TestCase
{
    public function stringsDataProvider()
    {
        return [
            [
                '20-12-1979',
                [
                    'month' => 12,
                    'day' => 20,
                    'year' => 1979,
                    'time' => '00:00:00',
                ]
            ],
            [
                1454860188,
                [
                    'month' => '2',
                    'day' => '7',
                    'year' => '2016',
                    'time' => '15:49:48',
                ],
            ],
            [
                '20-12-1979 12:00:03',
                [
                    'month' => 12,
                    'day' => 20,
                    'year' => 1979,
                    'time' => '12:00:03',
                ],
                'd-m-Y H:i:s',
            ]
        ];
    }

    /**
     * @param string|int $original
     * @param array $parts
     * @param string $format
     * @dataProvider stringsDataProvider
     */
    public function testStringTimes($original, $parts, $format = '')
    {
        $type = new DateType($original);
        $type->setFormat($format);
        $date = $type->getValue();

        $this->assertEquals($parts['day'], $date->format('d'));
        $this->assertEquals($parts['month'], $date->format('m'));
        $this->assertEquals($parts['year'], $date->format('Y'));
        $this->assertEquals($parts['time'], $date->format('H:i:s'));
    }

    public function testDateTimeIsNotConverted()
    {
        $date = new \DateTime();
        $type = new DateType($date);

        $this->assertSame($date, $type->getValue());
    }

    public function exceptionDataProvider()
    {
        return [
            [true],
            [new \stdClass()],
            [12.34],
            [null],
        ];
    }

    /**
     * @dataProvider exceptionDataProvider
     */
    public function testExceptionThrownWhenTypeIsInvalid($value)
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage(
            'Unable to convert type "' . gettype($value) . '" into DateTime'
        );

        $type = new DateType($value);
        $type->getValue();
    }
}
