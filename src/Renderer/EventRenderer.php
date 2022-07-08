<?php

namespace Calendar\Pdf\RendererBundle\Renderer;

use App\Calendar\Event;
use Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer\EventTypeRendererException;
use Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer\EventTypeRendererInterface;
use Calendar\Pdf\RendererBundle\Renderer\RenderInformation\RenderInformationInterface;
use Mpdf\Mpdf;

class EventRenderer
{
    /** @var EventTypeRendererInterface[] */
    protected array $renderer = [];

    /** @var Mpdf */
    protected $mpdf;

    public function setPdfRenderClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }

    public function registerRenderer(EventTypeRendererInterface $eventRenderer)
    {
        $eventRenderer->setPdfRendererClass($this->mpdf);
        $this->renderer[$eventRenderer->getRenderType()] = $eventRenderer;
    }

    public function renderEvents(array $events, RenderInformationInterface $calendarRenderInformation)
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
