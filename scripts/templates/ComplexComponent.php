<?php
namespace Doubleedesign\Comet\Components;

class ComplexComponent extends UIComponent implements IRenderable {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, '', $innerComponents);
    }
}
