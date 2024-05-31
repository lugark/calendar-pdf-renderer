<?php

namespace Calendar\Pdf\Renderer\Tests\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\AbstractRenderInformation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AbstractRenderInformationTest extends TestCase
{
    protected AbstractRenderInformation $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = $this->getMockForAbstractClass(AbstractRenderInformation::class);
    }

    public static function getPeriodData()
    {
        return [
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-2-1976')),
                false,
                DateTime::fromString('1-1-1976'),
                DateTime::fromString('1-2-1976')
            ],
            [
                new TimePeriod(DateTime::fromString('1-1-1976'), DateTime::fromString('1-2-1977')),
                true,
                DateTime::fromString('1-1-1976'),
                DateTime::fromString('1-2-1977')
            ],
        ];
    }

    #[DataProvider('getPeriodData')]
    public function testPeriodSettings($period, $expectedCrossYear, DateTime $expectedStart, DateTime $expectedEnd)
    {
        $this->markTestSkipped('Abastract class mock deprecated in PHPUnit 12');
        $this->sut->setCalendarPeriod($period);
        $expectedStart->time();
        $this->assertEquals($period, $this->sut->getCalendarPeriod());
        $this->assertEquals($expectedStart->toISO8601(), $this->sut->getCalendarStartsAt()->toISO8601());
        $this->assertEquals($expectedEnd->toISO8601(), $this->sut->getCalendarEndsAt()->toISO8601());
        $this->assertEquals($expectedCrossYear, $this->sut->doesCrossYear());
    }
}
