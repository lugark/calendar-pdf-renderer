<?php

namespace Calendar\Pdf\RendererBundle\Tests\Renderer;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;
use Calendar\Pdf\RendererBundle\Renderer\RenderRequest;
use PHPUnit\Framework\TestCase;

class RenderRequestTest extends TestCase
{
    protected RenderRequest $sut;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function renderRequestProvider()
    {
        $start = DateTime::fromString('01-01-1976');
        return [
            [
                RenderRequest\RequestTypes::LANDSCAPE_YEAR,
                new \DateTime('01-01-1976'),
                null,
                new TimePeriod(DateTime::fromString('01-01-1976'), DateTime::fromString('01-01-1977'))
            ],
            [
                RenderRequest\RequestTypes::LANDSCAPE_YEAR,
                new \DateTime('01-01-1976'),
                new \DateTime('01-02-1976'),
                $start->until(DateTime::fromString('01-02-1976'))
            ],
        ];
    }

    /** @dataProvider renderRequestProvider */
    public function testRenderRequest($requestType, $startDate, $endDate, $expectedPeriod)
    {
        $this->sut = new RenderRequest($requestType, $startDate, $endDate);

        $this->assertEquals($expectedPeriod, $this->sut->getPeriod());
        $this->assertEquals(RenderRequest\RequestTypes::LANDSCAPE_YEAR, $this->sut->getRequestType());
    }

    public function testRenderRequestConstrustFail()
    {
        $this->expectExceptionMessage('Not a valid render request type');
        $test = new RenderRequest('somethingStupid',
            new \DateTime('01-01-1976'),
            new \DateTime('01-02-1976')
        );
    }

    public function testRenderToFile()
    {
        $this->sut = new RenderRequest(
            RenderRequest\RequestTypes::LANDSCAPE_YEAR,
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
}
