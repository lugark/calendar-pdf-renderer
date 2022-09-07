<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Mpdf\Mpdf;

class CalendarRenderer
{
    protected ?RendererInterface $renderer;
    protected ?EventRenderer $eventRenderer;

    public function renderCalendar(RenderRequest $renderRequest): ?string
    {
        $this->renderer = self::getRendererByRequest($renderRequest);
        $this->eventRenderer = self::initEventRenderer(
            $this->renderer,
            $this->renderer->initRenderer()
        );

        $this->renderer->renderCalendar($renderRequest);
        $this->renderEvents($renderRequest);

        return $this->renderer->getOutput();
    }

    public static function getRendererByRequest(RenderRequest $renderRequest): RendererInterface
    {
        $renderClassType = ($renderRequest->getRequestType());
        return new $renderClassType();
    }

    public static function initEventRenderer(RendererInterface $renderer, Mpdf $mpdf): EventRenderer
    {
        $eventRenderer = new EventRenderer();
        $eventRenderer->setPdfRenderClass($mpdf);
        foreach ($renderer->getSupportedEventRenderer() as $supportedRenderer) {
            $reflection = new \ReflectionClass($supportedRenderer);
            if ($reflection->implementsInterface(EventTypeRendererInterface::class)) {
                $eventRenderer->registerRenderer(new $supportedRenderer());
            } else {
                throw new RendererException('Not a supported event type renderer: ' . $supportedRenderer);
            }
        }
        return $eventRenderer;
    }

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
