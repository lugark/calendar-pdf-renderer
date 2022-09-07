<?php

namespace Calendar\Pdf\Renderer\Tests\Service;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Month;
use Calendar\Pdf\Renderer\Service\RenderUtils;
use PHPUnit\Framework\TestCase;

class RenderUtilsTest extends TestCase
{

    public function hex2rgbProvider()
    {
        return [
            [
                '#ffffff',
                [255,255,255]
            ],
            [
                '#FFFFFF',
                [255,255,255]
            ],
            [
                'FFF',
                [255,255,255]
            ],
            [
                '0a0b0c',
                [10,11,12]
            ]

        ];
    }

    /** @dataProvider hex2rgbProvider */
    public function testHex2rgb($hex, $rgbArray)
    {
        $this->assertEquals($rgbArray, RenderUtils::hex2rgb($hex));
    }

    public function monthLocalizedData()
    {
        return [
            [
                Month::fromString('2020-01-01'),
                'de_DE',
                'Januar',
                'Januar `20'
            ],
            [
                Month::fromString('2020-02-01'),
                'de_DE',
                'Februar',
                'Februar `20'
            ],
            [
                Month::fromString('2020-01-01'),
                'en_US',
                'January',
                'January `20'
            ],
        ];
    }

    /** @dataProvider monthLocalizedData */
    public function testMonthLocalized(Month $month, $locale, $expectedString, $expectedStringWithYear)
    {
        setlocale(LC_TIME, $locale);
        #$this->assertEquals($expectedString, RenderUtils::getMonthLocalized($month));
        #$this->assertEquals($expectedStringWithYear, RenderUtils::getMonthLocalized($month, true));
        $this->assertStringNotContainsString('20', RenderUtils::getMonthLocalized($month));
        $this->assertStringContainsString('20', RenderUtils::getMonthLocalized($month, true));

    }

    public function dowLocalizedData()
    {
        return [
            [
                Day::fromString('2020-01-01'),
                'de_DE',
                'Mi'
            ],
            [
                Day::fromString('2020-01-01'),
                'en_US',
                'Wed'
            ],
        ];
    }

    /** @dataProvider dowLocalizedData */
    public function testDayOfWeekLocalized(Day $day, $locale, $expectedString)
    {
        setlocale(LC_TIME, $locale);
        #$this->assertEquals($expectedString, RenderUtils::getDayOfWeekLocalized($day));
        $this->assertNotEmpty(RenderUtils::getDayOfWeekLocalized($day));
    }
}
