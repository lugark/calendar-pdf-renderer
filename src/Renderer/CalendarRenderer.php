<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Calendar\Pdf\Renderer\Renderer\RenderRequest\RequestTypes;

class CalendarRenderer
{
    protected ?RendererInterface $renderer;
    protected ?EventRenderer $eventRenderer;
    protected ?Events $events;

    public function renderCalendar(RenderRequest $renderRequest): ?string
    {
        $this->renderer = self::getRendererByRequest($renderRequest);
        $this->eventRenderer = self::initEventRenderer($this->renderer);
        $this->eventRenderer->setPdfRenderClass($this->renderer->initRenderer());

        $this->renderer->renderCalendar($renderRequest);
        $this->renderEvents();

        return $this->renderer->getOutput();
    }

    public function setCalendarEvents($events): void
    {
        $this->events = $events;
    }

    public static function getRendererByRequest(RenderRequest $renderRequest): RendererInterface
    {
        $renderer = null;
        switch ($renderRequest->getRequestType()) {
            case RequestTypes::LANDSCAPE_YEAR:
                $renderer = new LandscapeYear();
                break;
            default:
                break;
        }
        return $renderer;
    }

    public static function initEventRenderer(RendererInterface $renderer): EventRenderer
    {
        $eventRenderer = new EventRenderer();
        foreach ($renderer->getSupportedEventRenderer() as $supportedRenderers) {
            if ($supportedRenderers instanceof EventTypeRendererInterface) {
                $eventRenderer->registerRenderer(new $supportedRenderers);
            }
        }
        return $eventRenderer;
    }

    private function renderEvents():void
    {
        if (empty($this->events) || count($this->events) == 0) {
            return;
        }

        $rendererInformation = $this->renderer->getRenderInformation();
        $this->eventRenderer->renderEvents(
            $this->events->getEventsByRange(
                $rendererInformation->getCalendarStartsAt(),
                $rendererInformation->getCalendarEndsAt()
            ),
            $rendererInformation
        );
    }
}
