<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Mpdf\Mpdf;
use Psr\Log\AbstractLogger;

class PdfRenderer
{
    protected ?Mpdf $mpdf=null;

    public function initMpdf(
        array $options=[],
        string $displaymode='fullpage',
        string $font='Helvetica',
        bool $addPage = true
    ): void
    {
        $this->mpdf = new Mpdf($options);

        $this->mpdf->setLogger(new class extends AbstractLogger {
            public function log($level, $message, $context=[])
            {
                echo $level . ': ' . $message . PHP_EOL;
            }
        });

        $this->mpdf->SetDisplayMode($displaymode);
        $this->mpdf->SetFontSize(6);
        $this->mpdf->SetFont($font);
        if ($addPage) {
            $this->mpdf->AddPage();
        }
    }

    protected function checkForValidMpdfRenderer(): void
    {
        if (empty($this->mpdf)) {
            throw new RendererException('Can not find PDF-Class - required to calculate dimensions');
        }
    }

    public function setDimensions(RenderInformationInterface $renderInformation): void
    {
        $this->checkForValidMpdfRenderer();
        $renderInformation
            ->setLeft($this->mpdf->lMargin)
            ->setTop($this->mpdf->tMargin);
    }

    public function getPdfWidth(): int
    {
        $this->checkForValidMpdfRenderer();
        return $this->mpdf->w;
    }

    public function getPdfHeight(): int
    {
        $this->checkForValidMpdfRenderer();
        return $this->mpdf->h;
    }

    public function getPdfGenerator(): Mpdf
    {
        $this->checkForValidMpdfRenderer();
        return $this->mpdf;
    }

}
