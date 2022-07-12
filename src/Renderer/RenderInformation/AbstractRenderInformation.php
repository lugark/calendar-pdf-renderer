<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;

abstract class AbstractRenderInformation implements RenderInformationInterface
{
    protected float $top;

    private float $left;

    private bool $crossYear = false;

    private TimePeriod $timePeriod;

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

    public function getCalendarStartsAt(): DateTime
    {
        return $this->timePeriod->start();
    }

    public function getCalendarEndsAt(): DateTime
    {
        return $this->timePeriod->end();
    }

    public function setCalendarPeriod(TimePeriod $timePeriod): RenderInformationInterface
    {
        $this->timePeriod = $timePeriod;
        $this->crossYear = $timePeriod->start()->year()->number() != $timePeriod->end()->year()->number();

        return $this;
    }

    public function doesCrossYear():bool
    {
        return $this->crossYear;
    }

    public function getTimePeriod(): TimePeriod
    {
        return $this->timePeriod;
    }
}
