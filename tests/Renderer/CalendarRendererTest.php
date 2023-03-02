<?php

namespace Calendar\Pdf\Renderer\Tests\Renderer;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Event\Types;
use Calendar\Pdf\Renderer\Renderer\CalendarRenderer;
use Calendar\Pdf\Renderer\Renderer\EventRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\PublicHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\LandscapeYear;
use Calendar\Pdf\Renderer\Renderer\PdfRenderer;
use Calendar\Pdf\Renderer\Renderer\RendererInterface;
use Calendar\Pdf\Renderer\Renderer\RenderRequest;
use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;
use setasign\Fpdi\PdfReader\PdfReader;

class StubCalRenderer extends CalendarRenderer
{
    public static function callMethod($obj, $name, array $args) {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    public function setEventRenderer(EventRenderer $eventRenderer)
    {
        $this->eventRenderer = $eventRenderer;
        $this->eventRenderer->setPdfRenderClass(new Mpdf());
    }

    public function renderEvents(RenderRequest $renderRequest): void
    {
        parent::renderEvents($renderRequest);
    }
}


class CalendarRendererTest extends TestCase
{

    public function testGetRendererByRequestSuccess()
    {
        $now = new \DateTime();
        $request = new RenderRequest(LandscapeYear::class, $now);
        $pdf = new PdfRenderer();
        $result = CalendarRenderer::getRendererByRequest($request, $pdf);
        self::assertInstanceOf(LandscapeYear::class, $result);
    }

    /**
     * @dataProvider getSupportedRendererData
     */
    public function testInitEventRendererSuccess($supported, $countAdded)
    {
        $mockRenderer = $this->getMockBuilder(RendererInterface::class)
            ->getMock();
        $mockRenderer->method('getSupportedEventRenderer')
            ->willReturn($supported);

        $mpdfMock = $this->getMockBuilder(Mpdf::class)
            ->getMock();
        $pdfGeneratorMock = $this->getMockBuilder(PdfRenderer::class)->getMock();
        $pdfGeneratorMock->method('getPdfGenerator')->willReturn($mpdfMock);

        $result = CalendarRenderer::initEventRenderer($mockRenderer, $pdfGeneratorMock);
        self::assertTrue($result instanceof EventRenderer);

        // Create a closure from a callable and bind it to MyClass.
        $closure = \Closure::bind(function (EventRenderer $result) {
            return $result->renderer;
        }, null, EventRenderer::class);

        self::assertEquals($countAdded, count($closure($result)));
    }


    public function getSupportedRendererData()
    {
        return [
            'successWithOne' => [[PublicHolidayRenderer::class], 1],
            'successWithZero' => [[], 0]
        ];
    }

    public function testInitEventRendererFail()
    {
        $this->expectErrorMessage('Not a supported event type renderer');
        $mpdfMock = $this->getMockBuilder(Mpdf::class)
            ->getMock();
        $pdfGeneratorMock = $this->getMockBuilder(PdfRenderer::class)->getMock();
        $pdfGeneratorMock->method('getPdfGenerator')->willReturn($mpdfMock);

        $mockRenderer = $this->getMockBuilder(RendererInterface::class)
            ->getMock();
        $mockRenderer->method('getSupportedEventRenderer')
            ->willReturn([TestCase::class]);

        $result = CalendarRenderer::initEventRenderer($mockRenderer, $pdfGeneratorMock);
    }

    /**
     * @dataProvider getEventsRenderEvents
     */
    public function testRenderEvents($events, $amountCalled)
    {
        // TODO: fix unittest
        $this->markTestSkipped("needs to be fixed -> changed setting of renderer");
        $mockPDFRenderer = $this->getMockBuilder(PdfRenderer::class)
            ->getMock();
        $mockEventRenderer = $this->getMockBuilder(EventRenderer::class)
            ->getMock();
        $mockEventRenderer->expects($this->exactly($amountCalled))
            ->method('renderEvents');
        $mockRenderer = $this->getMockBuilder(RendererInterface::class)
            ->getMock();

        $request = new RenderRequest(LandscapeYear::class, new \DateTime());
        if (!is_null($events)) {
            $request->setEvents(new Events($events));
        }

        $sut = new StubCalRenderer($mockPDFRenderer);
        $sut->setEventRenderer($mockEventRenderer);
        $sut->renderEvents($request);
    }

    public function getEventsRenderEvents()
    {
        $event = new Event(Types::EVENT_TYPE_PUBLIC_HOLIDAY);
        $event->setEventPeriod(new \DateTime());
        $event->setText('TestEvent');
        return [
            'zeroEvents' => [null, 0],
            'emptyEvents' => [[], 0],
        ];
    }
}
