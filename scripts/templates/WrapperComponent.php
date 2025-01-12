<?php
namespace Doubleedesign\Comet\Components;

class WrapperComponent extends LayoutComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Component.this-component');
    }
}
