<?php

namespace Calendar\Pdf\Renderer\Renderer;

class PdfSettings
{
    public function __construct(
        private string $paperFormat,
        private int $marginRight,
        private int $marginLeft,
        private int $marginTop,
        private int $marginBottom,
    ) {
    }

    public function getPaperFormat(): string
    {
        return $this->paperFormat;
    }

    public function getMarginRight(): int
    {
        return $this->marginRight;
    }

    public function getMarginLeft(): int
    {
        return $this->marginLeft;   
    }

    public function getMarginBottom(): int
    {
        return $this->marginBottom;
    }

    public function getMarginTop(): int
    {
        return $this->marginTop;
    }
}
