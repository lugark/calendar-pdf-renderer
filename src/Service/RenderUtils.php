<?php

namespace Calendar\Pdf\Renderer\Service;

class RenderUtils
{
    const ICU_STAND_ALONE_MONTH_FULL = 'LLLL';
    const ICU_STAND_ALONE_MONTH_SHORT = 'LLL';
    const ICU_STAND_ALONE_DOW_FULL = 'cccc';
    const ICU_STAND_ALONE_DOW_SHORT = 'ccc';

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

}
