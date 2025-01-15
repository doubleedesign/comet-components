<?php
namespace Doubleedesign\Comet\Components;

use Doubleedesign\Comet\Core\src\base\components\TextElement;

class Details extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Details.details');
    }
}
