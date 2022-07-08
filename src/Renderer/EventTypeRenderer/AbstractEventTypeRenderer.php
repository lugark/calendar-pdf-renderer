<?php

namespace Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer;

use Mpdf\Mpdf;

abstract class AbstractEventTypeRenderer implements EventTypeRendererInterface
{
    protected Mpdf $mpdf;

    public function setPdfRendererClass($pdfClass): void
    {
        $this->mpdf = $pdfClass;
    }
}
