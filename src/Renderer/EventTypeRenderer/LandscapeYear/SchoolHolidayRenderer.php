<?php

namespace Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\AbstractEventTypeRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererException;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Calendar\Pdf\Renderer\Service\RenderUtils;

class SchoolHolidayRenderer extends AbstractEventTypeRenderer
{
    const COLORS_SCHOOL_HOLIDAY = [
        '#11CC11',
        '#82f082',
        '#41ff24'
    ];

    const HOLIDAY_WIDTH = 6;

    public function render(Event $event, RenderInformationInterface $calendarRenderInformation): void
    {
        if (!($calendarRenderInformation instanceof LandscapeYearInformation))
        {
            throw new EventTypeRendererException(
                self::class .
                ' only supports rendering ' .
                LandscapeYearInformation::class
            );
        }

        echo $event->getText() . ' ' . $event->getStart()->format('Y') .
             ' - ' .  $event->getStart()->format('d.m.') . '-' . $event->getEnd()->format('d.m.') . PHP_EOL;


        $startDay = $event->getStart()->day();
        $endDay = $event->getEnd()->day();
        $monthEnd = $endDay->month()->number();
        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();

        for ($i=$startDay->month()->number(); $i<=$monthEnd; $i++) {
            $x = $calendarRenderInformation->getLeft() +
                (($i - 1) * $calendarRenderInformation->getColumnWidth()) +
                $calendarRenderInformation->getColumnWidth() - self::HOLIDAY_WIDTH - 1;
            $y = ($startDay->number() - 1) * $calendarRenderInformation->getRowHeight() +
                $calendarRenderInformation->getTop() +
                $calendarRenderInformation->getHeaderHeight();

            if ($i == $monthEnd) {
                $days = $endDay->number() - $startDay->number() + 1;
            } else {
                $days = $startDay->month()->lastDay()->number() - $startDay->number() + 1;
                $startDay = $startDay->plusMonths(1)->month()->firstDay();
            }

            $height = $days * $calendarRenderInformation->getRowHeight();
            $drawColor = RenderUtils::hex2rgb(self::COLORS_SCHOOL_HOLIDAY[2]);

            $pdfGenerator->SetFillColor($drawColor[0], $drawColor[1], $drawColor[2]);
            $pdfGenerator->Rect(
                $x,
                $y,
                6,
                $height,
                "F"
            );
        }
    }

    public function getRenderType(): string
    {
        return Types::EVENT_TYPE_SCHOOL_HOLIDAY;
    }
}
