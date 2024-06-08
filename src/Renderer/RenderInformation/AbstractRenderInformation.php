<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderInformation;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

abstract class AbstractRenderInformation implements RenderInformationInterface
{
    protected float $top;

    private float $left;

    private bool $crossYear = false;

    private CarbonPeriod $carbonPeriod;

    public function getTop(): float
    {
        return $this->top;
    }

    public function setTop(float $top): RenderInformationInterface
    {
        $this->top = $top;
        return $this;
    }
    public function getLeft(): float
    {
        return $this->left;
    }

    public function setLeft(float $left): RenderInformationInterface
    {
        $this->left = $left;
        return $this;
    }

    public function getCalendarStartsAt(): CarbonInterface
    {
        return $this->carbonPeriod->getStartDate();
    }

    public function getCalendarEndsAt(): CarbonInterface
    {
        return $this->carbonPeriod->getEndDate();
    }

    public function setCalendarPeriod(CarbonPeriod $carbonPeriod): RenderInformationInterface
    {
        $this->carbonPeriod = $carbonPeriod;
        $this->carbonPeriod->excludeEndDate(true);
        $this->crossYear = $carbonPeriod->getIncludedStartDate()->year != $carbonPeriod->getIncludedEndDate()->year;
        $this->carbonPeriod->excludeEndDate(false);

        return $this;
    }

    public function doesCrossYear():bool
    {
        return $this->crossYear;
    }

    public function getCalendarPeriod(): CarbonPeriod
    {
        return $this->carbonPeriod;
    }
}
