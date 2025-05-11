<?php
use Doubleedesign\Comet\Core\Steps;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'colorTheme', 'hAlign', 'maxPerRow', 'orientation', 'tagName', 'testId', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'steps component')];

$component = new Steps($attributes, $innerComponents);
$component->render();
