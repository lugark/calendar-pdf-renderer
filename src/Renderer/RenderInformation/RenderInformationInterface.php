<?php

namespace Calendar\Pdf\RendererBundle\Renderer\RenderInformation;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;

interface RenderInformationInterface
{
    public function setTop(float $top): RenderInformationInterface;
    public function getTop(): float;
    public function getLeft(): float;
    public function setLeft(float $left): RenderInformationInterface;

    public function getCalendarStartsAt(): DateTime;
    public function getCalendarEndsAt(): DateTime;
    public function setCalendarPeriod(TimePeriod $timePeriod): RenderInformationInterface;
    public function doesCrossYear():bool;
    public function getTimePeriod(): TimePeriod;

    public function initRenderInformation(): RenderInformationInterface;
}
