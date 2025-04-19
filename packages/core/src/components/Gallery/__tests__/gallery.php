<?php
use Doubleedesign\Comet\Core\Gallery;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['caption', 'classes', 'columns', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'gallery component')];

$component = new Gallery($attributes, $innerComponents);
$component->render();