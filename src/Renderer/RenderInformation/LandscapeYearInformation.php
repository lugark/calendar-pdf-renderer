<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderInformation;

use Carbon\CarbonPeriod;
use Carbon\Unit;

class LandscapeYearInformation extends AbstractRenderInformation
{
    protected int $numberOfMonthsToRender=12;

    private float $headerHeight;

    private float $columnWidth;

    private float $rowHeight;

    private int $maxRowsToRender=31;

    public function numberOfMonthsToRender(): int
    {
        return $this->numberOfMonthsToRender;
    }

    public function getHeaderHeight(): float
    {
        return $this->headerHeight;
    }

    public function setHeaderHeight(float $headerHeight): LandscapeYearInformation
    {
        $this->headerHeight = $headerHeight;
        return $this;
    }

    public function getColumnWidth(): float
    {
        return $this->columnWidth;
    }

    public function setColumnWidth(float $columnWidth): LandscapeYearInformation
    {
        $this->columnWidth = $columnWidth;
        return $this;
    }

    public function getRowHeight(): float
    {
        return $this->rowHeight;
    }

    public function setRowHeight(float $rowHeight): LandscapeYearInformation
    {
        $this->rowHeight = $rowHeight;
        return $this;
    }

    public function initRenderInformation(): RenderInformationInterface
    {
        $calendarPeriod = CarbonPeriod::instance($this->getCalendarPeriod());
        $calendarPeriod
            ->setDateInterval(1, Unit::Month)
            ->excludeStartDate(true);
        $this->numberOfMonthsToRender = count($calendarPeriod);

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
}
