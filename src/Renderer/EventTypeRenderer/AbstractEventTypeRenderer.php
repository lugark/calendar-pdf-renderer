<?php

namespace Calendar\Pdf\Renderer\Renderer\EventTypeRenderer;

use Calendar\Pdf\Renderer\Renderer\PdfRenderer;
use Mpdf\Mpdf;

abstract class AbstractEventTypeRenderer implements EventTypeRendererInterface
{
    protected PdfRenderer $pdfRenderer;

    public function setPdfGenerator(PdfRenderer $pdfRenderer): void
    {
        $this->pdfRenderer = $pdfRenderer;
    }
}
