<?php

namespace Calendar\Pdf\Renderer\Tests\Renderer;

use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Renderer\LandscapeYear;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RenderRequestTest extends TestCase
{
    protected RenderRequest $sut;

    public static function renderRequestProvider()
    {
        return [
            [
                LandscapeYear::class,
                new \DateTime('01-01-1976'),
                null,
                CarbonPeriod::create('01-01-1976', '01-01-1977')
            ],
            [
                LandscapeYear::class,
                new \DateTime('01-01-1976'),
                new \DateTime('01-02-1976'),
                CarbonPeriod::create('01-01-1976', '01-02-1976')
            ],
        ];
    }

    #[DataProvider('renderRequestProvider')]
    public function testRenderRequest($requestType, $startDate, $endDate, $expectedPeriod)
    {
        $this->sut = new RenderRequest($requestType, $startDate, $endDate);

        $this->assertEquals($expectedPeriod, $this->sut->getPeriod());
        $this->assertEquals(LandscapeYear::class, $this->sut->getRequestType());
    }

    public function testRenderRequestConstrustFailClassNotExist()
    {
        $this->expectExceptionMessage('Not a valid render type');
        $test = new RenderRequest('somethingStupid',
            new \DateTime('01-01-1976'),
            new \DateTime('01-02-1976')
        );
    }

    public function testRenderRequestConstrustFailNotCorrectInterface()
    {
        $this->expectExceptionMessage('Not a valid render type');
        $test = new RenderRequest(TestCase::class,
            new \DateTime('01-01-1976'),
            new \DateTime('01-02-1976')
        );
    }

    public function testRenderToFile()
    {
        $this->sut = new RenderRequest(
            LandscapeYear::class,
            new \DateTime('01-01-1976'),
            new \DateTime('01-02-1976')
        );
        $this->assertEquals(true, $this->sut->doRenderToFile());

        $this->sut->disableFileRendering();
        $this->assertFalse($this->sut->doRenderToFile());

        $this->sut->renderToFile('booooh.yeah');
        $this->assertTrue($this->sut->doRenderToFile());
        $this->assertEquals('booooh.yeah', $this->sut->getRenderFile());
    }

    public function testSetGetEvents()
    {
        $events = new Events();
        $this->sut = new RenderRequest(
            LandscapeYear::class,
            new \DateTime('01-01-1976'),
            new \DateTime('01-02-1976')
        );

        $result = $this->sut->setEvents($events);
        self::assertTrue($result instanceof RenderRequest);

        $result = $this->sut->getEvents();
        self::assertEquals($events, $result);
    }
}
