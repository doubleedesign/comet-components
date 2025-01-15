<?php
namespace Doubleedesign\Comet\Components;

use Doubleedesign\Comet\Core\src\base\components\TextElement;

class Freeform extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Freeform.freeform');
    }
}
