<?php
namespace Doubleedesign\Comet\Components;

class Details extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Details.details');
    }
}
