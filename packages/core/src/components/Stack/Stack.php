<?php
namespace Doubleedesign\Comet\Components;

use Doubleedesign\Comet\Core\src\base\components\LayoutComponent;

class Stack extends LayoutComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Component.stack');
    }
}
