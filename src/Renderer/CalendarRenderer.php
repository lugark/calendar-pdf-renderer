<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;

class CalendarRenderer
{
    protected ?RendererInterface $renderer;
    protected ?EventRenderer $eventRenderer;

    public function renderCalendar(RenderRequest $renderRequest): ?string
    {
        $this->renderer = self::getRendererByRequest($renderRequest);
        $this->eventRenderer = self::initEventRenderer($this->renderer);
        $this->eventRenderer->setPdfRenderClass($this->renderer->initRenderer());

        $this->renderer->renderCalendar($renderRequest);
        $this->renderEvents($renderRequest);

        return $this->renderer->getOutput();
    }

    public function setCalendarEvents($events): void
    {
        $this->events = $events;
    }

    public static function getRendererByRequest(RenderRequest $renderRequest): RendererInterface
    {
        $renderClassType = $renderRequest->getRequestType();
        $rendererReflection = new \ReflectionClass($renderClassType);
        if (!$rendererReflection->implementsInterface(RendererInterface::class)) {
            throw new RendererException('Can not find class to render: ' . $rendererReflection->getName());
        }

        return new $renderClassType();
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

    private function renderEvents(RenderRequest $renderRequest):void
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
