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

class LandscapeYear extends MpdfRendererAbstract
{
    CONST FONT_SIZE_HEADER = 8;
    CONST FONT_SIZE_CELL = 6;
    CONST COLOR_TEXT_HEADER = '#c63131';
    const COLOR_BORDER_TABLE = '#c63131';
    CONST COLOR_BORDER_HEADER = '#DEDEDE';
    const COLOR_FILL_SA = '#F8E6E6';
    const COLOR_FILL_SO = '#F3D5D5';

    private $fillColorWeekday = [
        6 => self::COLOR_FILL_SA,
        7 => self::COLOR_FILL_SO
    ];

    protected LandscapeYearInformation $renderInformation;

    public function initRenderer(): Mpdf
    {
        $this->initMpdf([
            'format' => 'A4-L',
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 0,
        ]);
        $this->mpdf->AddPage();
        $this->mpdf->SetFont('Helvetica');

        return $this->mpdf;
    }

    public function renderCalendar(RenderRequest $renderRequest): RendererInterface
    {
        $this->renderRequest = $renderRequest;
        $this->renderInformation = $this->calculateDimensions();
        $this->renderHeader();
        $this->renderData();

        $redBorder = RenderUtils::hex2rgb(self::COLOR_BORDER_TABLE);
        $this->mpdf->SetDrawColor($redBorder[0], $redBorder[1], $redBorder[2]);
        $this->mpdf->Rect(
            $this->mpdf->lMargin-2,
            $this->mpdf->tMargin,
            $this->renderInformation->numberOfMonthsToRender() * $this->renderInformation->getColumnWidth() + 2,
            31 * $this->renderInformation->getRowHeight() + $this->headerHeight + 2
        );

        return $this;
    }

    private function renderHeader()
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HEADER);
        $this->mpdf->SetFont('', 'B');
        $borderColor = RenderUtils::hex2rgb(self::COLOR_BORDER_HEADER);
        $textColor = RenderUtils::hex2rgb(self::COLOR_TEXT_HEADER);
        $this->mpdf->SetDrawColor($borderColor[0], $borderColor[1], $borderColor[2]);
        $this->mpdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);

        $includeYear = !$this->renderInformation->doesCrossYear();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            $this->mpdf->WriteCell(
                $this->renderInformation->getColumnWidth() ,
                $this->headerHeight ,
                RenderUtils::getMonthLocalized($month, $includeYear),
                'B',
                0,
                'C'
            );
        }
    }

    public function renderData(): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_CELL);
        $this->mpdf->SetTextColor(0, 0, 0);
        $startHeight = $this->mpdf->tMargin + $this->renderInformation->getHeaderHeight();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            /** @var Day $day */
            foreach ($month->days()->all() as $day) {
                $this->mpdf->SetXY(
                    $this->mpdf->lMargin + (($month->number()-1) * $this->renderInformation->getColumnWidth() ),
                    $startHeight + (($day->number()-1) * $this->renderInformation->getRowHeight() )
                );

                $text = $day->number() . ' ' . RenderUtils::getDayOfWeekLocalized($day);
                $colorData = $this->getDayColorData($day);
                if ($colorData['fill']) {
                    $this->mpdf->SetFillColor($colorData['color'][0], $colorData['color'][1], $colorData['color'][2]);
                }

                $this->mpdf->Cell(
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

    public function setCalendarEvents($events): RendererInterface
    {
        $this->calendarEvents = $events;
        return $this;
    }

    public function getRenderInformation(): RenderInformationInterface
    {
        return new LandscapeYearInformation();
    }

    protected function calculateDimensions(): RenderInformationInterface
    {
        $canvasSizeX = $this->mpdf->w;
        $canvasSizeY = $this->mpdf->h;

        /** @var LandscapeYearInformation $landscapeRenderInformation */
        $landscapeRenderInformation =  parent::calculateDimensions();
        $landscapeRenderInformation
            ->setHeaderHeight($this->headerHeight)
            ->setColumnWidth(round(
                ($canvasSizeX-($this->marginLeft+$this->marginRight))/$landscapeRenderInformation->numberOfMonthsToRender(),
                3
            ))
            ->setRowHeight(
                round(
                    ($canvasSizeY-($this->calenderStartY+$this->headerHeight))/$landscapeRenderInformation->getMaxRowsToRender(),
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
        if ($this->renderRequest->doRenderToFile()) {
            $this->mpdf->Output($this->renderRequest->getRenderFile(), Destination::FILE);
            return '';
        } else {
            return $this->mpdf->Output();
        }
    }
}
