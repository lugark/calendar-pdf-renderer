<?php

namespace Calendar\Pdf\Renderer\Tests\Renderer\RenderInformation;

use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Carbon\CarbonInterface;
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
            'oneMonth' =>
                [
                    new CarbonPeriod(new \DateTime('1-1-1976'), new \DateTime('1-2-1976')),
                    1
                ],
            '25Month' =>
                [
                    new CarbonPeriod(new \DateTime('1-1-1976'), new \DateTime('3-2-1978')),
                    25
                ],
            '12Month' =>
                [
                    new CarbonPeriod(new \DateTime('1-1-1976'), new \DateTime('1-1-1977')),
                    12
                ],
            '25Month2' =>
                [
                    new CarbonPeriod(new \DateTime('1-1-1976'), new \DateTime('2-2-1978')),
                    25
                ],
        ];
    }

    #[DataProvider('provideRenderInformationData')]
    public function testInitRenderInformation($period, $expectedMonths)
    {
        $this->sut->setCalendarPeriod($period);
        $this->assertEquals(12, $this->sut->numberOfMonthsToRender());

        $this->sut->initRenderInformation();
        $this->assertEquals($expectedMonths, $this->sut->numberOfMonthsToRender());
    }

    public function testMaxRows()
    {
        $this->assertEquals(31, $this->sut->getMaxRowsToRender());
        $this->sut->setMaxRowsToRender(5);
        $this->assertEquals(5, $this->sut->getMaxRowsToRender());
    }

    public static function getPeriodData()
    {
        return [
            [
                CarbonPeriod::create('1-1-1976', '1-2-1976'),
                false,
                Carbon::create('1-1-1976'),
                Carbon::create('1-2-1976')
            ],
            [
                CarbonPeriod::create('1-1-1976', '1-2-1977'),
                true,
                Carbon::create('1-1-1976'),
                Carbon::create('1-2-1977')
            ],
        ];
    }

    #[DataProvider('getPeriodData')]
    public function testPeriodSettings($period, $expectedCrossYear, CarbonInterface $expectedStart, CarbonInterface $expectedEnd)
    {
        $this->sut->setCalendarPeriod($period);
        $this->assertEquals($period, $this->sut->getCalendarPeriod());
        $this->assertEquals($expectedStart, $this->sut->getCalendarStartsAt());
        $this->assertEquals($expectedEnd, $this->sut->getCalendarEndsAt());
        $this->assertEquals($expectedCrossYear, $this->sut->doesCrossYear());
    }    
}
