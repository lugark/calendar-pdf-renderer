<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererException;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use ReflectionClass;
use ReflectionException;

class CalendarRenderer
{
    protected ?RendererInterface $renderer;
    protected ?EventRenderer $eventRenderer;
    protected PdfRenderer $pdfRenderer;

    public function __construct(PdfRenderer $pdfRenderer)
    {
        $this->pdfRenderer = $pdfRenderer;
    }

    /**
     * @throws RendererException|ReflectionException
     * @throws EventTypeRendererException
     */
    public function renderCalendar(RenderRequest $renderRequest): ?string
    {
        $this->renderer = self::getRendererByRequest(
            $renderRequest,
            $this->pdfRenderer
        );
        $this->eventRenderer = self::initEventRenderer(
            $this->renderer,
            $this->pdfRenderer
        );

        $this->renderer->renderCalendar($renderRequest);
        $this->renderEvents($renderRequest);

        return $this->renderer->getOutput();
    }

    public static function getRendererByRequest(RenderRequest $renderRequest, PdfRenderer $pdfRenderer): RendererInterface
    {
        $renderClassType = ($renderRequest->getRequestType());
        return new $renderClassType($pdfRenderer);
    }

    /**
     * @throws ReflectionException
     * @throws RendererException
     */
    public static function initEventRenderer(RendererInterface $renderer, PdfRenderer $pdfRenderer): EventRenderer
    {
        $eventRenderer = new EventRenderer();
        $eventRenderer->setPdfGenerator($pdfRenderer);
        foreach ($renderer->getSupportedEventRenderer() as $supportedRenderer) {
            $reflection = new ReflectionClass($supportedRenderer);
            if ($reflection->implementsInterface(EventTypeRendererInterface::class)) {
                $eventRenderer->registerRenderer(new $supportedRenderer());
            } else {
                throw new RendererException('Not a supported event type renderer: ' . $supportedRenderer);
            }
        }
        return $eventRenderer;
    }

    /**
     * @throws EventTypeRendererException
     */
    protected function renderEvents(RenderRequest $renderRequest):void
    {
        $events = $renderRequest->getEvents();
        if (empty($events) || count($events) == 0) {
            return;
        }

        $rendererInformation = $this->renderer->getRenderInformation();
        $this->eventRenderer->renderEvents(
            $events->getEventsByRange(
                $rendererInformation->getCalendarStartsAt(),
                $rendererInformation->getCalendarEndsAt()
            ),
            $rendererInformation
        );
    }
}
