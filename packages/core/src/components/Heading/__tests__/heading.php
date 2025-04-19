<?php
use Doubleedesign\Comet\Core\Heading;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'level', 'tagName', 'testId', 'textAlign', 'textColor'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'heading component')];

$component = new Heading($attributes, $innerComponents);
$component->render();