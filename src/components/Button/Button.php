<?php
namespace Doubleedesign\Comet;

class Button extends UIComponent implements Renderable {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content);
    }
}
