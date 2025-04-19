<?php
use Doubleedesign\Comet\Core\Breadcrumbs;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'breadcrumbs component')];

$component = new Breadcrumbs($attributes, $innerComponents);
$component->render();