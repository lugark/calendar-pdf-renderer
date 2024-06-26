<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Mpdf\Mpdf;

interface RendererInterface
{
    public function renderCalendar(RenderRequest $renderRequest): RendererInterface;
    public function getRenderInformation(): RenderInformationInterface;
    public function getSupportedEventRenderer(): array;
    public function getOutput(): ?string;
}
