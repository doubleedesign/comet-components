<?php
namespace Doubleedesign\Comet;

class MediaText extends UIComponent implements Renderable {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, '', $innerComponents);
    }
}
