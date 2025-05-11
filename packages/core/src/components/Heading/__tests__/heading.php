<?php
use Doubleedesign\Comet\Core\Heading;

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'level', 'testId', 'textAlign', 'textColor'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$component = new Heading($attributes, 'Heading component');
$component->render();
