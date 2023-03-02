<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Aeon\Calendar\Gregorian\Day;
use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\PublicHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\SchoolHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Calendar\Pdf\Renderer\Service\RenderUtils;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class LandscapeYear implements RendererInterface
{
    CONST FONT_SIZE_HEADER = 8;
    CONST FONT_SIZE_CELL = 6;
    CONST COLOR_TEXT_HEADER = '#c63131';
    const COLOR_BORDER_TABLE = '#c63131';
    CONST COLOR_BORDER_HEADER = '#DEDEDE';
    const COLOR_FILL_SA = '#F8E6E6';
    const COLOR_FILL_SO = '#F3D5D5';

    const MARGIN_LEFT = 5;
    const MARGIN_RIGHT = 5;
    const MARGIN_TOP = 10;
    const MARGIN_BOTTOM = 0;
    const HEADER_HEIGHT = 6;
    const CALENDAR_START_XY = 20;

    private $fillColorWeekday = [
        6 => self::COLOR_FILL_SA,
        7 => self::COLOR_FILL_SO
    ];

    protected LandscapeYearInformation $renderInformation;

    protected PdfRenderer $pdfRenderer;

    public function __construct(PdfRenderer $pdfRenderer)
    {
        $this->pdfRenderer = $pdfRenderer;
    }

    protected function initRenderer()
    {
        $this->pdfRenderer->initMpdf(
            [
                'format' => 'A4-L',
                'margin_left' => self::MARGIN_LEFT,
                'margin_right' => self::MARGIN_RIGHT,
                'margin_top' => self::MARGIN_TOP,
                'margin_bottom' => self::MARGIN_BOTTOM,
            ],
        );
    }

    public function renderCalendar(RenderRequest $renderRequest): RendererInterface
    {
        $this->initRenderer();
        $this->renderRequest = $renderRequest;
        $this->renderInformation = $this->calculateDimensions();
        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();
        $this->renderHeader();
        $this->renderData();

        $redBorder = RenderUtils::hex2rgb(self::COLOR_BORDER_TABLE);
        $pdfGenerator->SetDrawColor($redBorder[0], $redBorder[1], $redBorder[2]);
        $pdfGenerator->Rect(
            $pdfGenerator->lMargin-2,
            $pdfGenerator->tMargin,
            $this->renderInformation->numberOfMonthsToRender() * $this->renderInformation->getColumnWidth() + 2,
            31 * $this->renderInformation->getRowHeight() + self::HEADER_HEIGHT + 2
        );

        return $this;
    }

    private function renderHeader()
    {
        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();
        $pdfGenerator->SetFontSize(self::FONT_SIZE_HEADER);
        $pdfGenerator->SetFont('', 'B');
        $borderColor = RenderUtils::hex2rgb(self::COLOR_BORDER_HEADER);
        $textColor = RenderUtils::hex2rgb(self::COLOR_TEXT_HEADER);
        $pdfGenerator->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $pdfGenerator->SetTextColor($textColor[0], $textColor[1], $textColor[2]);

        $includeYear = !$this->renderInformation->doesCrossYear();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            $pdfGenerator->WriteCell(
                $this->renderInformation->getColumnWidth() ,
                self::HEADER_HEIGHT ,
                RenderUtils::getMonthLocalized($month, $includeYear),
                'B',
                0,
                'C'
            );
        }
    }

    public function renderData(): void
    {
        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();
        $pdfGenerator->SetFontSize(self::FONT_SIZE_CELL);
        $pdfGenerator->SetTextColor(0, 0, 0);
        $startHeight = $pdfGenerator->tMargin + $this->renderInformation->getHeaderHeight();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            /** @var Day $day */
            foreach ($month->days()->all() as $day) {
                $pdfGenerator->SetXY(
                    $pdfGenerator->lMargin + (($month->number()-1) * $this->renderInformation->getColumnWidth() ),
                    $startHeight + (($day->number()-1) * $this->renderInformation->getRowHeight() )
                );

                $text = $day->number() . ' ' . RenderUtils::getDayOfWeekLocalized($day);
                $colorData = $this->getDayColorData($day);
                if ($colorData['fill']) {
                    $pdfGenerator->SetFillColor($colorData['color'][0], $colorData['color'][1], $colorData['color'][2]);
                }

                $pdfGenerator->Cell(
                    $this->renderInformation->getColumnWidth() -1,
                    $this->renderInformation->getRowHeight()  ,
                    $text,
                    'B',
                    0,
                    '',
                    $colorData['fill']);
            }
        }
    }

    private function getDayColorData(Day $day): array
    {
        $colorData = [
            'fill' => false,
            'color' => [0,0,0]
        ];

        $weekday = $day->weekDay();
        if ($weekday->isWeekend()) {
            $colorData['fill'] = 1;
            if (isset($this->fillColorWeekday[$weekday->number()])) {
                $colorData['color'] = RenderUtils::hex2rgb($this->fillColorWeekday[$weekday->number()]);
            }
        }

        return $colorData;
    }

    public function getRenderInformation(): LandscapeYearInformation
    {
        return new LandscapeYearInformation();
    }

    protected function calculateDimensions(): LandscapeYearInformation
    {
        $canvasSizeX = $this->pdfRenderer->getPdfWidth();
        $canvasSizeY = $this->pdfRenderer->getPdfHeight();
        $landscapeRenderInformation = (new LandscapeYearInformation())
            ->setCalendarPeriod($this->renderRequest->getPeriod())
            ->initRenderInformation();
        $this->pdfRenderer->setDimensions($landscapeRenderInformation);

        $landscapeRenderInformation
            ->setHeaderHeight(self::HEADER_HEIGHT)
            ->setColumnWidth(round(
                ($canvasSizeX-(self::MARGIN_LEFT + self::MARGIN_RIGHT)) /
                $landscapeRenderInformation->numberOfMonthsToRender(),
                3
            ))
            ->setRowHeight(
                round(
                    ($canvasSizeY-(self::CALENDAR_START_XY + self::HEADER_HEIGHT)) /
                    $landscapeRenderInformation->getMaxRowsToRender(),
                    3
                ));

        return $landscapeRenderInformation;
    }

    public function getSupportedEventRenderer(): array
    {
        return [
            PublicHolidayRenderer::class,
            SchoolHolidayRenderer::class
        ];
    }

    public function getOutput(): ?string
    {
        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();
        if ($this->renderRequest->doRenderToFile()) {
            $pdfGenerator->Output($this->renderRequest->getRenderFile(), Destination::FILE);
            return '';
        } else {
            return $pdfGenerator->Output();
        }
    }
}
