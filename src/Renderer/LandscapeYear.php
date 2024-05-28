<?php

namespace Calendar\Pdf\Renderer\Renderer;

use Aeon\Calendar\Gregorian\Day;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\PublicHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear\SchoolHolidayRenderer;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use Calendar\Pdf\Renderer\Service\RenderUtils;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\CellStyle;
use Calendar\Pdf\Renderer\Renderer\StyleSettings\FontStyle;

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

    private array $fillColorWeekday = [
        6 => self::COLOR_FILL_SA,
        7 => self::COLOR_FILL_SO
    ];

    protected LandscapeYearInformation $renderInformation;

    protected PdfRenderer $pdfRenderer;

    private RenderRequest $renderRequest;

    public function __construct(PdfRenderer $pdfRenderer)
    {
        $this->pdfRenderer = $pdfRenderer;
    }

    /**
     * @throws MpdfException
     */
    protected function initRenderer(): void
    {
        $this->pdfRenderer->initPdf(
            new PdfSettings(
                'A4-L',
                self::MARGIN_RIGHT,
                self::MARGIN_LEFT,
                self::MARGIN_TOP,
                self::MARGIN_BOTTOM
            )
        );
    }

    /**
     * @throws MpdfException
     * @throws RendererException
     */
    public function renderCalendar(RenderRequest $renderRequest): RendererInterface
    {
        $this->initRenderer();
        $this->renderRequest = $renderRequest;
        $this->renderInformation = $this->calculateDimensions();
        $this->renderHeader();
        $this->renderData();

        $this->pdfRenderer->drawColoredRectangle(
            self::COLOR_BORDER_TABLE,
            $this->renderInformation->getLeft()-2,
            $this->renderInformation->getTop(),
            $this->renderInformation->numberOfMonthsToRender() * $this->renderInformation->getColumnWidth() + 2,
            31 * $this->renderInformation->getRowHeight() + self::HEADER_HEIGHT + 2
        );

        return $this;
    }

    /**
     * @throws MpdfException
     */
    private function renderHeader(): void
    {
        $cellStyle = new CellStyle(
            new FontStyle('', 'B', self::FONT_SIZE_HEADER),
            self::COLOR_TEXT_HEADER, 
            0, 
            self::COLOR_BORDER_HEADER, 
            'C',
            0,
        );

        $includeYear = !$this->renderInformation->doesCrossYear();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            $this->pdfRenderer->writeTextInCell(
                $cellStyle,
                $this->renderInformation->getColumnWidth() ,
                self::HEADER_HEIGHT ,
                RenderUtils::getMonthLocalized($month, $includeYear),
            );
        }
    }

    /**
     * @throws MpdfException
     */
    public function renderData(): void
    {
        $cellStyle = new CellStyle(
            new FontStyle('', 'B', self::FONT_SIZE_CELL),
            '#000000', 
            'B', 
            self::COLOR_BORDER_HEADER, 
            'L',
            0,
        );
        $startHeight = $this->renderInformation->getTop() + $this->renderInformation->getHeaderHeight();

        foreach ($this->renderInformation->getMonthsToRender() as $month) {
            /** @var Day $day */
            foreach ($month->days()->all() as $day) {
                $text = $day->number() . ' ' . RenderUtils::getDayOfWeekLocalized($day);
                $colorData = $this->getDayColorData($day);
                $cellStyle->setFill($colorData['fill']); 
                $cellStyle->setFillColor($colorData['hexColor']);
                $this->pdfRenderer->writeTextInCellAtXY(
                    $cellStyle,
                    $this->renderInformation->getLeft() + (($month->number()-1) * $this->renderInformation->getColumnWidth()),
                    $startHeight + (($day->number()-1) * $this->renderInformation->getRowHeight()),
                    $this->renderInformation->getColumnWidth()-1,
                    $this->renderInformation->getRowHeight(),
                    $text
                );
            }
        }
    }

    private function getDayColorData(Day $day): array
    {
        $colorData = [
            'fill' => false,
            'color' => [0,0,0],
            'hexColor' => ''
        ];

        $weekday = $day->weekDay();
        if ($weekday->isWeekend()) {
            $colorData['fill'] = 1;
            if (isset($this->fillColorWeekday[$weekday->number()])) {
                $colorData['color'] = RenderUtils::hex2rgb($this->fillColorWeekday[$weekday->number()]);
                $colorData['hexColor'] = $this->fillColorWeekday[$weekday->number()];
            }
        }

        return $colorData;
    }

    public function getRenderInformation(): LandscapeYearInformation
    {
        return new LandscapeYearInformation();
    }

    /**
     * @throws RendererException
     */
    protected function calculateDimensions(): LandscapeYearInformation
    {
        $canvasSizeX = $this->pdfRenderer->getPdfWidth();
        $canvasSizeY = $this->pdfRenderer->getPdfHeight();

        /** @var LandscapeYearInformation $landscapeRenderInformation */
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

    /**
     * @throws MpdfException
     * @throws RendererException
     */
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
