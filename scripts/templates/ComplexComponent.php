<?php
namespace Doubleedesign\Comet;

class ComplexComponent extends UIComponent implements Renderable {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, '', $innerComponents);
    }
}
