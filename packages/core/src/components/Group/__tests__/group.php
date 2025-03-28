<?php
use Doubleedesign\Comet\Core\Group;
use const Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT;

// Attribute keys fetched from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'hAlign', 'tagName', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT;

$component = new Group($attributes, $innerComponents);
$component->render();