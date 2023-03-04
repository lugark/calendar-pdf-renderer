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

    /**
     * Get the value of textColorHex
     */ 
    public function getTextColorHex()
    {
            return $this->textColorHex;
    }

    /**
     * Get the value of drawBorder
     */ 
    public function getDrawBorder()
    {
        return $this->drawBorder;
    }

    /**
     * Get the value of borderColorHex
     */ 
    public function getBorderColorHex()
    {
        return $this->borderColorHex;
    }

    /**
     * Get the value of align
     */ 
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Get the value of ln
     */ 
    public function getLn()
    {
        return $this->ln;
    }

    /**
     * Get the value of fill
     */ 
    public function getFill()
    {
        return $this->fill;
    }

    /**
     * Get the value of fillColor
     */ 
    public function getFillColor()
    {
        return $this->fillColor;
    }

    /**
     * Get the value of fontStyle
     */ 
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
