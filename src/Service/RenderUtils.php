<?php

namespace Calendar\Pdf\Renderer\Service;

use Aeon\Calendar\Gregorian\Day;
use Aeon\Calendar\Gregorian\Month;

class RenderUtils
{
    const ICU_STAND_ALONE_MONTH_FULL = 'LLLL';
    const ICU_STAND_ALONE_MONTH_SHORT = 'LLL';
    const ICU_STAND_ALONE_DOW_FULL = 'cccc';
    const ICU_STAND_ALONE_DOW_SHORT = 'ccc';

    private static ?\IntlDateFormatter $intlDateFormatter=null;

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
        $formatter = self::getFormatter();
        $formatter->setPattern(self::ICU_STAND_ALONE_MONTH_FULL);
        $text = $formatter->format($month->toDateTimeImmutable()->getTimestamp());
        if (!$includeYear) {
            return $text;
        }

        return $text . ' `' . $month->year()->toDateTimeImmutable()->format('y');
    }

    public static function getDayOfWeekLocalized(Day $day): string
    {
        $formatter = self::getFormatter();
        $formatter->setPattern(self::ICU_STAND_ALONE_DOW_SHORT);
        return $formatter->format($day->toDateTimeImmutable()->getTimestamp());
    }

    public static function getFormatter(?string $locale=''): \IntlDateFormatter
    {
        if (!is_null(self::$intlDateFormatter) && !empty($locale)) {
            return self::$intlDateFormatter;
        }

        $locale = empty($locale) ? locale_get_default() : $locale;
        return \IntlDateFormatter::create($locale);
    }
}
