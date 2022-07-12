<?php

namespace Calendar\Pdf\Renderer\Service;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Month;

class RenderUtils
{
    public static function hex2rgb($hex): array
    {
        $hex = str_replace("#", "", $hex);

        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }

        return array($r, $g, $b);
    }

    public static function getMonthLocalized(Month $month, bool $includeYear = false): string
    {
        $text = strftime('%B', $month->toDateTimeImmutable()->getTimestamp());
        if (!$includeYear) {
            return $text;
        }

        return $text . ' `' . $month->year()->toDateTimeImmutable()->format('y');
    }

    public static function getDayOfWeekLocalized(Day $day): string
    {
        return strftime('%a', $day->toDateTimeImmutable()->getTimestamp());
    }
}
