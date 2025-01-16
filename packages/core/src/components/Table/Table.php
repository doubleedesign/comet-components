<?php
namespace Doubleedesign\Comet\Core;

class Table extends TextElement {
	function __construct(array $attributes, string $content) {
		parent::__construct($attributes, $content, 'components.Table.table');
	}
}
