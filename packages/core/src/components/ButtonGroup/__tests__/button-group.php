<?php
use Doubleedesign\Comet\Core\{ButtonGroup, Button};

// Attribute keys fetched from component JSON definition
$attributeKeys = ['hAlign', 'vAlign', 'orientation'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [
    new Button([], 'Button 1'),
    new Button(['isOutline' => true], 'Button 2'),
];

$component = new ButtonGroup($attributes, $innerComponents);
$component->render();
