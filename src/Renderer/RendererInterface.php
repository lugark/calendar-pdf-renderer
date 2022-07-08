<?php

namespace Calendar\Pdf\RendererBundle\Renderer;

use Calendar\Pdf\RendererBundle\Renderer\RenderInformation\RenderInformationInterface;

interface RendererInterface
{
    public function renderCalendar(RenderRequest $renderRequest): ?string;
    public function setCalendarEvents($events): void;

    public function initRenderer();
    public function getRenderInformation(): RenderInformationInterface;
    /**
     * public function getSupportedEventRenderer: array
     * oder
     * public function registerEventRenderer(EventRenderer $eventRenderer)
     */
}
