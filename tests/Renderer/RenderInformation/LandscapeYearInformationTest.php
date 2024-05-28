<?php

namespace Calendar\Pdf\Renderer\Tests\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\TimePeriod;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class LandscapeYearInformationTest extends TestCase
{
    protected LandscapeYearInformation $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new LandscapeYearInformation();
    }

    public static function provideRenderInformationData()
    {
        return [
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-2-1976')),
                1
            ],
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('3-2-1978')),
                25
            ],
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-1-1977')),
                12
            ],
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('2-2-1978')),
                25
            ],
        ];
    }

    #[DataProvider('provideRenderInformationData')]
    public function testInitRenderInformation($period, $expectedMonths)
    {
        /** @var TimePeriod $period */
        $expectedMonthArray = $period->start()->month()->iterate(
          $period->end()->month(),
          Interval::rightOpen()
        )->all();

        $this->sut->setCalendarPeriod($period);
        $this->assertEquals(12, $this->sut->numberOfMonthsToRender());

        $this->sut->initRenderInformation();
        $this->assertEquals($expectedMonths, $this->sut->numberOfMonthsToRender());
        $this->assertEquals($expectedMonthArray, $this->sut->getMonthsToRender());
    }

    public function testMaxRows()
    {
        $this->assertEquals(31, $this->sut->getMaxRowsToRender());
        $this->sut->setMaxRowsToRender(5);
        $this->assertEquals(5, $this->sut->getMaxRowsToRender());
    }
}
