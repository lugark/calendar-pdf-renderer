<?php
namespace Calendar\Pdf\Renderer\Tests\Renderer;

use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\PublicHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\SchoolHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\LandscapeYear;
use Calendar\Pdf\Renderer\Renderer\PdfRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;
use Carbon\Carbon;
use Mpdf\Mpdf;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LandscapeYearRendererTest extends TestCase
{
    protected LandscapeYear $sut;
    protected PdfRenderer $pdfRenderer;
    protected Mpdf|MockObject $mpdfMock;


    public function setup(): void
    {
        parent::setUp();
        $this->pdfRenderer = new PdfRenderer();
        $this->sut = new LandscapeYear($this->pdfRenderer);
    }

    public function testGetSupportedEventRenderer()
    {
        self::assertEquals(
            [
                PublicHolidayRenderer::class,
                SchoolHolidayRenderer::class
            ],
            $this->sut->getSupportedEventRenderer()
        );
    }

    public function testRenderCalculateDimensions()
    {
        $renderRequest = new RenderRequest(LandscapeYear::class, new \DateTime('01-01-2024'));
        $this->sut->renderCalendar($renderRequest);
        
        $renderInformation = $this->sut->getRenderInformation();
        self::assertEquals(Carbon::create('01-01-2024'), $renderInformation->getCalendarStartsAt());
        self::assertEquals(Carbon::create('01-01-2025'), $renderInformation->getCalendarEndsAt());
        self::assertEquals(12, $renderInformation->numberOfMonthsToRender());
        self::assertEquals(LandscapeYear::HEADER_HEIGHT, $renderInformation->getHeaderHeight());
        self::assertEquals(23.917, $renderInformation->getColumnWidth());
        self::assertEquals(5.935, $renderInformation->getRowHeight());
    }
}