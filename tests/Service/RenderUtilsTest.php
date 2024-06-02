<?php

namespace Calendar\Pdf\Renderer\Tests\Service;

use Calendar\Pdf\Renderer\Service\RenderUtils;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RenderUtilsTest extends TestCase
{

    /**
     * @return array<mixed>
     */
    public static function hex2rgbProvider(): array
    {
        return [
            [
                '#ffffff',
                [255,255,255]
            ],
            [
                '#FFFFFF',
                [255,255,255]
            ],
            [
                'FFF',
                [255,255,255]
            ],
            [
                '0a0b0c',
                [10,11,12]
            ]

        ];
    }

    #[DataProvider('hex2rgbProvider')]
    public function testHex2rgb($hex, $rgbArray)
    {
        $this->assertEquals($rgbArray, RenderUtils::hex2rgb($hex));
    }
}
