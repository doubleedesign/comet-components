<?php
use Doubleedesign\Comet\Core\ButtonGroup;
use const Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_BUTTONS;

// Attribute keys fetched from component JSON definition
$attributeKeys = ['classes', 'hAlign', 'orientation', 'tagName'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = MOCK_INNER_COMPONENTS_BUTTONS;

$component = new ButtonGroup($attributes, $innerComponents);
$component->render();
