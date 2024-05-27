<?php

namespace Calendar\Pdf\Renderer\Renderer\StyleSettings;

class FontStyle
{
    public function __construct(
        private ?string $fontFamily = '',
        private ?string $fontStyle = '',
        private ?int $fontSize = 12       
    ) {        
    }

    public function getFontFamily(): ?string
    {
        return $this->fontFamily;
    }
    
    public function getFontStyle()
    {
            return $this->fontStyle;
    }

    public function getFontSize()
    {
            return $this->fontSize;
    }
}
