<?php
use Doubleedesign\Comet\Core\IconWithText;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'colorTheme', 'icon', 'iconPrefix', 'label', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'icon-with-text component')];

$component = new IconWithText($attributes, $innerComponents);
$component->render();