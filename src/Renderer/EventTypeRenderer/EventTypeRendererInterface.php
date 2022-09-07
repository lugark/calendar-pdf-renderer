<?php

namespace Calendar\Pdf\Renderer\Renderer\EventTypeRenderer;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;

interface EventTypeRendererInterface
{
    public function setPdfRendererClass($pdfClass): void;

    public function render(
        Event $event,
        RenderInformationInterface $calendarRenderInformation
    ): void;

    public function getRenderType(): string;

}
