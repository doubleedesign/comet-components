<?php
namespace Doubleedesign\Comet;

class ListItem extends UIComponent implements Renderable {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, '', $innerComponents);
    }
}
