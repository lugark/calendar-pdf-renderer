<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

interface RenderInformationInterface
{
    public function setTop(float $top): RenderInformationInterface;
    public function getTop(): float;
    public function getLeft(): float;
    public function setLeft(float $left): RenderInformationInterface;

    public function getCalendarStartsAt(): CarbonInterface;
    public function getCalendarEndsAt(): CarbonInterface;
    public function setCalendarPeriod(CarbonPeriod $carbonPeriod): RenderInformationInterface;
    public function doesCrossYear():bool;
    public function getCalendarPeriod(): CarbonPeriod;

    public function initRenderInformation(): RenderInformationInterface;
}
