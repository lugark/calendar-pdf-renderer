<?php

namespace Calendar\Pdf\Renderer\Renderer\RenderRequest;

use ReflectionClass;

class RequestTypes
{
    const LANDSCAPE_YEAR = 'LandscapeYearInformationAbstract';

    private static $validRequestTypes = [
        self::LANDSCAPE_YEAR
    ];
    public static function isValidRequestType(string $type): bool
    {
        return in_array($type, self::$validRequestTypes);
    }
}
