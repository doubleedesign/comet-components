<?php
namespace Doubleedesign\Comet;

class Grid extends UIComponent implements Renderable {
    function __construct(array $attributes, string $content) {
        $this->attributes = $attributes;
        parent::__construct($this->attributes, $content);
    }

    function render(): void {
        parent::render();
    }
}
