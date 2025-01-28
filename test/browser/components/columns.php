<?php
use Doubleedesign\Comet\Core\Columns;
use Doubleedesign\Comet\Core\Column;
use const Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_SIMPLE;

$attributes = [];
$innerComponents = [
	(new Column([], MOCK_INNER_COMPONENTS_SIMPLE))->to_array(),
	(new Column([], MOCK_INNER_COMPONENTS_SIMPLE))->to_array(),
];

$component = new Columns($attributes, $innerComponents);
$component->render();
