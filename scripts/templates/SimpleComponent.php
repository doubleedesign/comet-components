<?php
namespace Doubleedesign\Comet\Components;

use Doubleedesign\Comet\Core\src\base\components\TextElement;

class SimpleComponent extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.ThisComponent.this-component');
    }
}
