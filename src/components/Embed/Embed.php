<?php
namespace Doubleedesign\Comet\Components;

class Embed extends TextElement {
    function __construct(array $attributes, string $content) {
        parent::__construct($attributes, $content, 'components.Embed.embed');
    }
}
