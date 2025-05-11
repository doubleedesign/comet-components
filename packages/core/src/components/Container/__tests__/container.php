<?php
use Doubleedesign\Comet\Core\{Container, Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'gradient', 'size', 'tagName', 'withWrapper'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);

$innerComponents = [new Paragraph([], 'Container component')];

$component = new Container($attributes, $innerComponents);
$component->render();
