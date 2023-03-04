<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Calendar\Pdf\Renderer\Renderer\PdfSettings;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\CellStyle;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\FontStyle;
use Mpdf\Mpdf;
use Psr\Log\AbstractLogger;
use Calendar\Pdf\Renderer\Service\RenderUtils;

class PdfRenderer
{
    protected ?Mpdf $mpdf=null;

    private function initMpdf(
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

    public function initPdf(PdfSettings $pdfSettings)
    {
        $this->initMpdf([
            'format' => $pdfSettings->getPaperFormat(),
            'margin_left' => $pdfSettings->getMarginLeft(),
            'margin_right' => $pdfSettings->getMarginRight(),
            'margin_top' => $pdfSettings->getMarginTop(),
            'margin_bottom' => $pdfSettings->getMarginBottom(),            
        ]);
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
        return intval(round($this->mpdf->w));
    }

    public function getPdfHeight(): int
    {
        $this->checkForValidMpdfRenderer();
        return intval(round($this->mpdf->h));
    }

    public function getPdfGenerator(): Mpdf
    {
        $this->checkForValidMpdfRenderer();
        return $this->mpdf;
    }

    public function drawRectangle(
        string $drawColorHex,
        int $x,
        int $y,
        int $width,
        int $height
    ): void {
        $drawColorRGB = RenderUtils::hex2rgb($drawColorHex);
        $this->mpdf->SetDrawColor($drawColorRGB[0], $drawColorRGB[1], $drawColorRGB[2]);
        $this->mpdf->Rect($x, $y, $width, $height);
    }

    public function writeTextInCell(
        CellStyle $cellStyle,
        float $width,
        float $height,
        string $text
    ) {
        $this->setFont($cellStyle->getFontStyle());
        $this->setBorderColor($cellStyle->getBorderColorHex());
        $this->setTextColor($cellStyle->getTextColorHex());
        $this->setFillColor($cellStyle->getFillColor());

        $this->mpdf->WriteCell(
            $width,
            $height,
            $text,
            $cellStyle->getDrawBorder(),
            $cellStyle->getLn(),
            $cellStyle->getAlign(),
            $cellStyle->getFill()
        );
    }

    public function writeTextInCellAtXY(
        CellStyle $cellStyle,
        float $x,
        float $y,
        float $width,
        float $height,
        string $text
    ) {
        $this->setFont($cellStyle->getFontStyle());
        $this->setBorderColor($cellStyle->getBorderColorHex());
        $this->setTextColor($cellStyle->getTextColorHex());
        $this->setFillColor($cellStyle->getFillColor());

        $this->mpdf->SetXY($x, $y);
        $this->mpdf->Cell(
            $width,
            $height,
            $text,
            $cellStyle->getDrawBorder(),
            $cellStyle->getLn(),
            $cellStyle->getAlign(),
            $cellStyle->getFill()
        );
    }

    private function setFont(FontStyle $fontStyle)
    {
        $this->mpdf->SetFont(
            $fontStyle->getFontFamily(),
            $fontStyle->getFontStyle()
        );
        $this->mpdf->SetFontSize($fontStyle->getFontSize());
    }

    private function setBorderColor(?string $borderColorHex)
    {
        if (!empty($borderColorHex)) {
            $borderColor = RenderUtils::hex2rgb($borderColorHex);
            $this->mpdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        }
    }

    private function setTextColor(?string $textColorHex)
    {
        if (!empty($textColorHex)) {            
            $textColor = RenderUtils::hex2rgb($textColorHex);
            $this->mpdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        }
    }

    private function setFillColor(?string $fillColorHex)
    {
        if (!empty($fillColorHex)) {            
            $fillColor = RenderUtils::hex2rgb($fillColorHex);
            $this->mpdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
        }
    }

}
