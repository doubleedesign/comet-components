<?php
use Doubleedesign\Comet\Core\{ButtonGroup, Button};

// Attribute keys fetched from component JSON definition
$attributeKeys = ['hAlign', 'vAlign', 'orientation', 'colorTheme'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [
    new Button([], 'Default theme button'),
    new Button(['isOutline' => true], 'Default theme outline button'),
    new Button(['colorTheme' => 'secondary'], 'Button with custom colour'),
    new Button(['isOutline' => true, 'colorTheme' => 'accent'], 'Outline button with custom colour'),
];

$component = new ButtonGroup($attributes, $innerComponents);
$component->render();
