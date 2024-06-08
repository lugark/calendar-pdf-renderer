<?php

namespace Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\LandscapeYear;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\AbstractEventTypeRenderer;
use Calendar\Pdf\Renderer\Renderer\EventTypeRenderer\EventTypeRendererException;
use Calendar\Pdf\Renderer\Renderer\RendererException;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\LandscapeYearInformation;
use Calendar\Pdf\Renderer\Renderer\RenderInformation\RenderInformationInterface;
use Mpdf\MpdfException;

class PublicHolidayRenderer extends AbstractEventTypeRenderer
{
    const FONT_SIZE_HOLIDAY = 5;

    /**
     * @throws MpdfException
     * @throws RendererException
     * @throws EventTypeRendererException
     */
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

        $pdfGenerator = $this->pdfRenderer->getPdfGenerator();
        $pdfGenerator->SetFontSize(self::FONT_SIZE_HOLIDAY);
        $pdfGenerator->SetFont('', 'B');
        $pdfGenerator->SetTextColor(199, 50, 50);

        $month = $event->getStart()->month;
        $day = $event->getStart()->day;

        $x = $calendarRenderInformation->getLeft() + (($month-1) * $calendarRenderInformation->getColumnWidth());
        $y = $calendarRenderInformation->getTop() +
            (($day-1) * $calendarRenderInformation->getRowHeight()) +
            $calendarRenderInformation->getHeaderHeight()  + 1.7;

        $pdfGenerator->SetXY($x, $y);
        $pdfGenerator->WriteText($x,$y, $event->getText());
    }

    public function getRenderType(): string
    {
        return Types::EVENT_TYPE_PUBLIC_HOLIDAY;
    }
}
