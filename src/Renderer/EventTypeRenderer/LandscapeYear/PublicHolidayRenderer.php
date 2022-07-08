<?php

namespace Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer\LandscapeYear;

use Calendar\Pdf\RendererBundle\Event\Event;
use Calendar\Pdf\RendererBundle\Event\Types;
use Calendar\Pdf\RendererBundle\Renderer\EventTypeRenderer\AbstractEventTypeRenderer;
use Calendar\Pdf\RendererBundle\Renderer\RenderInformation\RenderInformationInterface;

class PublicHolidayRenderer extends AbstractEventTypeRenderer
{
    const FONT_SIZE_HOLIDAY = 5;

    public function render(Event $event, RenderInformationInterface $calendarRenderInformation): void
    {
        $this->mpdf->SetFontSize(self::FONT_SIZE_HOLIDAY);
        $this->mpdf->SetFont('', 'B');
        $this->mpdf->SetTextColor(199, 50, 50);

        $month = $event->getStart()->month()->number();
        $day = $event->getStart()->day()->number();

        $x = $calendarRenderInformation->getLeft() + (($month-1) * $calendarRenderInformation->getColumnWidth());
        $y = $calendarRenderInformation->getTop() +
            (($day-1) * $calendarRenderInformation->getRowHeight()) +
            $calendarRenderInformation->getHeaderHeight()  + 1.7;

        $this->mpdf->SetXY($x, $y);
        $this->mpdf->WriteText($x,$y, $event->getText());
    }

    public function getRenderType(): string
    {
        return Types::EVENT_TYPE_PUBLIC_HOLIDAY;
    }
}
