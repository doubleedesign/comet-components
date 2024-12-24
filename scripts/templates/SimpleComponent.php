<?php
namespace Doubleedesign\Comet\Components;

class SimpleComponent extends BasicElementComponent implements IRenderable {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content);
    }
}
