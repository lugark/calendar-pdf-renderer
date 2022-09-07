<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\Interval;
use Aeon\Calendar\Gregorian\Month;

class LandscapeYearInformation extends AbstractRenderInformation
{
    protected int $numberOfMonthsToRender=12;

    /** @var Month[] */
    protected array $monthsToRender;

    private float $headerHeight;

    private float $columnWidth;

    private float $rowHeight;

    private int $maxRowsToRender=31;

    public function numberOfMonthsToRender():int
    {
        return $this->numberOfMonthsToRender;
    }

    public function getHeaderHeight(): float
    {
        return $this->headerHeight;
    }

    public function setHeaderHeight(float $headerHeight): AbstractRenderInformation
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    public function getColumnWidth(): float
    {
        return $this->columnWidth;
    }

    public function setColumnWidth(float $columnWidth): AbstractRenderInformation
    {
        $this->columnWidth = $columnWidth;
        return $this;
    }

    public function getRowHeight(): float
    {
        return $this->rowHeight;
    }

    public function setRowHeight(float $rowHeight): AbstractRenderInformation
    {
        $this->rowHeight = $rowHeight;
        return $this;
    }

    public function initRenderInformation(): RenderInformationInterface
    {
        $this->monthsToRender = $this->getCalendarStartsAt()->month()->iterate(
            $this->getCalendarEndsAt()->month(),
            Interval::rightOpen())->all();
        $this->numberOfMonthsToRender = count($this->monthsToRender);

        return $this;
    }

    public function getMaxRowsToRender(): int
    {
        return $this->maxRowsToRender;
    }

    public function setMaxRowsToRender(int $maxRowsToRender): RenderInformationInterface
    {
        $this->maxRowsToRender = $maxRowsToRender;
        return $this;
    }

    public function getMonthsToRender(): array
    {
        return $this->monthsToRender;
    }
}
