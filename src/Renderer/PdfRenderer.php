<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\CellStyle;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\FontStyle;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Psr\Log\AbstractLogger;
use Calendar\Pdf\Renderer\Service\RenderUtils;

class PdfRenderer
{
    protected ?Mpdf $mpdf = null;

    /**
     * @throws MpdfException
     */
    private function initMpdf(
        array $options = [],
        string $displaymode = 'fullpage',
        string $font = 'Helvetica',
        bool $addPage = true
    ): void {
        $this->mpdf = new Mpdf($options);

        $this->mpdf->setLogger(new class extends AbstractLogger {
            public function log($level, $message, $context = []): void
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

    /**
     * @throws MpdfException
     */
    public function initPdf(PdfSettings $pdfSettings): void
    {
        $this->initMpdf([
            'format' => $pdfSettings->getPaperFormat(),
            'margin_left' => $pdfSettings->getMarginLeft(),
            'margin_right' => $pdfSettings->getMarginRight(),
            'margin_top' => $pdfSettings->getMarginTop(),
            'margin_bottom' => $pdfSettings->getMarginBottom(),
        ]);
    }

    /**
     * @throws RendererException
     */
    protected function checkForValidMpdfRenderer(): void
    {
        if (empty($this->mpdf)) {
            throw new RendererException('Can not find PDF-Class - required to calculate dimensions');
        }
    }

    /**
     * @throws RendererException
     */
    public function setDimensions(RenderInformationInterface $renderInformation): void
    {
        $this->checkForValidMpdfRenderer();
        $renderInformation
            ->setLeft($this->mpdf->lMargin)
            ->setTop($this->mpdf->tMargin);
    }

    /**
     * @throws RendererException
     */
    public function getPdfWidth(): int
    {
        $this->checkForValidMpdfRenderer();
        return intval(round($this->mpdf->w));
    }

    /**
     * @throws RendererException
     */
    public function getPdfHeight(): int
    {
        $this->checkForValidMpdfRenderer();
        return intval(round($this->mpdf->h));
    }

    /**
     * @throws RendererException
     */
    public function getPdfGenerator(): Mpdf
    {
        $this->checkForValidMpdfRenderer();
        return $this->mpdf;
    }

    public function drawColoredRectangle(
        string $drawColorHex,
        float $x,
        float $y,
        float $width,
        float $height
    ): void {
        $drawColorRGB = RenderUtils::hex2rgb($drawColorHex);
        $this->mpdf->SetDrawColor($drawColorRGB[0], $drawColorRGB[1], $drawColorRGB[2]);
        $this->mpdf->Rect($x, $y, $width, $height);
    }

    /**
     * @throws MpdfException
     */
    public function writeTextInCell(
        CellStyle $cellStyle,
        float $width,
        float $height,
        string $text
    ): void {
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

    /**
     * @throws MpdfException
     */
    public function writeTextInCellAtXY(
        CellStyle $cellStyle,
        float $x,
        float $y,
        float $width,
        float $height,
        string $text
    ): void {
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

    /**
     * @throws MpdfException
     */
    private function setFont(FontStyle $fontStyle): void
    {
        $this->mpdf->SetFont(
            $fontStyle->getFontFamily(),
            $fontStyle->getFontStyle()
        );
        $this->mpdf->SetFontSize($fontStyle->getFontSize());
    }

    private function setBorderColor(?string $borderColorHex): void
    {
        if (!empty($borderColorHex)) {
            $borderColor = RenderUtils::hex2rgb($borderColorHex);
            $this->mpdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        }
    }

    private function setTextColor(?string $textColorHex): void
    {
        if (!empty($textColorHex)) {
            $textColor = RenderUtils::hex2rgb($textColorHex);
            $this->mpdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        }
    }

    private function setFillColor(?string $fillColorHex): void
    {
        if (!empty($fillColorHex)) {
            $fillColor = RenderUtils::hex2rgb($fillColorHex);
            $this->mpdf->SetFillColor($fillColor[0], $fillColor[1], $fillColor[2]);
        }
    }

}
