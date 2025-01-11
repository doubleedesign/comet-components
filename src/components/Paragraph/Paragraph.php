<?php
namespace Doubleedesign\Comet\Components;

class Paragraph extends TextElement {
	function __construct(array $attributes, string $content) {
        $bladeFile = 'components.Paragraph.paragraph';
        $attrs = array_merge($attributes, ['tagName' => 'p']);

		parent::__construct($attrs, $content, $bladeFile);
	}
}
