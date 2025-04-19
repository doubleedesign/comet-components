<?php
use Doubleedesign\Comet\Core\Columns;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['allowStacking', 'backgroundColor', 'classes', 'hAlign', 'tagName', 'testId', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'columns component')];

$component = new Columns($attributes, $innerComponents);
$component->render();