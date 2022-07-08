<?php

namespace Calendar\Pdf\RendererBundle\Renderer;

use Calendar\Pdf\RendererBundle\Event\Events;
use Calendar\Pdf\RendererBundle\Renderer\RenderInformation\RenderInformationInterface;

class CalendarRenderer implements RendererInterface
{
    protected ?RendererInterface $renderer;
    protected ?Events $events;

    public function renderCalendar(RenderRequest $renderRequest): ?string
    {
        return $this->renderer->renderCalendar($renderRequest);
    }

    public function setCalendarEvents($events): void
    {
        // TODO: Implement setCalendarEvents() method.
    }

    public function initRenderer()
    {
        // TODO: Implement initRenderer() method.
    }

    public function getRenderInformation(): RenderInformationInterface
    {
        // TODO: Implement getRenderInformation() method.
    }

}
