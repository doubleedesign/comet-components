<?php
namespace Doubleedesign\Comet\Components;

class Pullquote extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Pullquote.pullquote');
    }
}
