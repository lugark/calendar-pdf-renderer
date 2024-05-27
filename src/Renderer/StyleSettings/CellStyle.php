<?php

namespace Calendar\Pdf\Renderer\Renderer\StyleSettings;

use Calendar\Pdf\Renderer\Renderer\StyleSettings\FontStyle;

class CellStyle
{
    public function __construct(
        private FontStyle $fontStyle,
        private ?string $textColorHex,
        private ?string $drawBorder,        
        private ?string $borderColorHex,
        private ?string $align = 'C',
        private ?int $ln = 0,
        private ?bool $fill = false,
        private ?string $fillColor = null
    ) {        
    }

    public function getTextColorHex()
    {
            return $this->textColorHex;
    }

    public function getDrawBorder()
    {
        return $this->drawBorder;
    }

    public function getBorderColorHex()
    {
        return $this->borderColorHex;
    }

    public function getAlign()
    {
        return $this->align;
    }

    public function getLn()
    {
        return $this->ln;
    }

    public function getFill()
    {
        return $this->fill;
    }

    public function getFillColor()
    {
        return $this->fillColor;
    }

    public function getFontStyle()
    {
        return $this->fontStyle;
    }

    public function setFillColor($fillColor)
    {
        $this->fillColor = $fillColor;
        return $this;
    }

    public function setFill($fill)
    {
        $this->fill = $fill;
        return $this;
    }
}
