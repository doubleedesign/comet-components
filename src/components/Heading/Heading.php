<?php
namespace Doubleedesign\Comet\Components;

class Heading extends UIComponent implements Renderable {
    function __construct(array $attributes, string $content) {
        if(isset($attributes['level'])) {
            $attributes['level'] = (int) $attributes['level'];
            $attributes['tagName'] = 'h' . $attributes['level'];
        }
        else {
            $attributes['level'] = 2;
            $attributes['tagName'] = 'h2';
        }

        parent::__construct($attributes, $content);
    }
}
