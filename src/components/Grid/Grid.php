<?php
namespace Doubleedesign\Comet\Components;

class Grid extends LayoutComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Component.grid');
    }
}
