<?php
use Doubleedesign\Comet\Core\Table;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['allowStacking', 'caption', 'classes', 'sticky', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'table component')];

$component = new Table($attributes, $innerComponents);
$component->render();