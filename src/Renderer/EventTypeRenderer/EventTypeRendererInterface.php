<?php

namespace Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer;

use Calendar\Pdf\RendererBundle\Event\Event;
use Calendar\Pdf\RendererBundle\Renderer\RenderInformation\RenderInformationInterface;

interface EventTypeRendererInterface
{
    public function setPdfRendererClass($pdfClass): void;

    public function render(
        Event $event,
        RenderInformationInterface $calendarRenderInformation
    ): void;

    public function getRenderType(): string;

}
