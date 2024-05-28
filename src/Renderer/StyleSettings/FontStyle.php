<?php

namespace Calendar\Pdf\Renderer\Renderer\StyleSettings;

readonly class FontStyle
{
    public function __construct(
        private ?string $fontFamily = '',
        private ?string $fontStyle = '',
        private ?int    $fontSize = 12
    ) {        
    }

    public function getFontFamily(): ?string
    {
        return $this->fontFamily;
    }
    
    public function getFontStyle(): ?string
    {
            return $this->fontStyle;
    }

    public function getFontSize(): ?int
    {
            return $this->fontSize;
    }
}
