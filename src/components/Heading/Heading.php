<?php
namespace Doubleedesign\Comet\Components;

class Heading extends UIComponent implements Renderable {
    function __construct(array $attributes, string $content) {
        $attributes['tagName'] = 'h' . $attributes['level'] ?? 'h2';

        parent::__construct($attributes, $content);
    }
}
