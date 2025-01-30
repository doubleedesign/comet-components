<?php
use Doubleedesign\Comet\Core\Container;
use Doubleedesign\Comet\Core\Paragraph;


$size = $_REQUEST['size'] ?? 'default';

$attributes = [];
$attributes = array_filter([
	'size'            => $_REQUEST['size'] ?? null,
	'backgroundColor' => $_REQUEST['backgroundColor'] ?? 'light'
]);

$innerComponents = [
	(new Paragraph([], ucfirst($size) . ' Container')),
];

$component = new Container($attributes, $innerComponents);
$component->render();
