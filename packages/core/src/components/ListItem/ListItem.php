<?php
namespace Doubleedesign\Comet\Components;

use Doubleedesign\Comet\Core\src\base\components\UIComponent;

class ListItem extends UIComponent {
    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.ListItem.list-item');
    }

    function get_inline_styles(): array {
        // TODO: Implement get_inline_styles() method.
        return [];
    }

    function render(): void {
        // TODO: Implement render() method.
    }
}
