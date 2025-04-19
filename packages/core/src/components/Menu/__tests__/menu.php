<?php
use Doubleedesign\Comet\Core\Menu;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'menu component')];

$component = new Menu($attributes, $innerComponents);
$component->render();