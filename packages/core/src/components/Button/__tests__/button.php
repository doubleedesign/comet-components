<?php
use Doubleedesign\Comet\Core\Button;

// Attribute keys fetched from component JSON definition
$attributeKeys = ['classes', 'colorTheme', 'isOutline', 'tagName'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, function($key) use ($attributeKeys) {
	return in_array($key, $attributeKeys) && $_REQUEST[$key] !== '' && $_REQUEST[$key] !== 'false';
}, ARRAY_FILTER_USE_KEY);

$component = new Button($attributes, 'Lorem ipsum');
$component->render();
