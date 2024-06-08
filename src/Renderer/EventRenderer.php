<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererException;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;

class EventRenderer
{
    /** @var EventTypeRendererInterface[] */
    protected array $renderer = [];

    protected PdfRenderer $pdfGenerator;

    public function setPdfGenerator(PdfRenderer $pdfGenerator): void
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function registerRenderer(EventTypeRendererInterface $eventRenderer): void
    {
        $eventRenderer->setPdfGenerator($this->pdfGenerator);
        $this->renderer[$eventRenderer->getRenderType()] = $eventRenderer;
    }

    /**
     * @throws EventTypeRendererException
     */
    public function renderEvents(array $events, RenderInformationInterface $calendarRenderInformation): void
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $eventType = $event->getType();
            if (!array_key_exists($eventType, $this->renderer)) {
                throw new EventTypeRendererException(
                    'Can not find renderer for event-type: ' . $eventType
                );
            }

            $this->renderer[$eventType]->render($event, $calendarRenderInformation);
        }
    }

}
