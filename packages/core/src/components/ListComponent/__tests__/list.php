<?php
use Doubleedesign\Comet\Core\{ListComponent, ListItem};

// Attribute keys from component JSON definition
$attributeKeys = ['ordered'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);
// Filter out any attributes that are empty or false
$attributes = array_filter($attributes, function($value) {
	return $value !== '' && $value !== 'false' && $value !== 'none' && $value !== 'null';
});

$innerComponents = [
	new ListItem([], 'List item 1'),
	new ListItem([], 'List item 2'),
	new ListItem([], 'List item 3 with nested list', [
		new ListComponent([], [
			new ListItem([], 'Nested list item 1'),
			new ListItem([], 'Nested list item 2'),
		]),
	]),
	new ListItem([], 'List item 4'),
];

$component = new ListComponent($attributes, $innerComponents);
$component->render();
