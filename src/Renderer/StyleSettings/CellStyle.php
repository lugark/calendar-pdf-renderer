<?php

namespace Calendar\Pdf\Renderer\Renderer\StyleSettings;

class CellStyle
{
    public function __construct(
        private readonly FontStyle $fontStyle,
        private readonly ?string   $textColorHex,
        private readonly ?string   $drawBorder,
        private readonly ?string   $borderColorHex,
        private readonly ?string   $align = 'C',
        private readonly ?int      $ln = 0,
        private ?bool              $fill = false,
        private ?string            $fillColor = null
    ) {        
    }

    public function getTextColorHex(): ?string
    {
            return $this->textColorHex;
    }

    public function getDrawBorder(): ?string
    {
        return $this->drawBorder;
    }

    public function getBorderColorHex(): ?string
    {
        return $this->borderColorHex;
    }

    public function getAlign(): ?string
    {
        return $this->align;
    }

    public function getLn(): ?int
    {
        return $this->ln;
    }

    public function getFill(): ?bool
    {
        return $this->fill;
    }

    public function getFillColor(): ?string
    {
        return $this->fillColor;
    }

    public function getFontStyle(): FontStyle
    {
        return $this->fontStyle;
    }

    public function setFillColor($fillColor): static
    {
        $this->fillColor = $fillColor;
        return $this;
    }

    public function setFill($fill): static
    {
        $this->fill = $fill;
        return $this;
    }
}
