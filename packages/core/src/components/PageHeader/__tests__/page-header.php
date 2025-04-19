<?php
use Doubleedesign\Comet\Core\PageHeader;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'breadcrumbs', 'classes', 'size', 'tagName', 'testId', 'title'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'page-header component')];

$component = new PageHeader($attributes, $innerComponents);
$component->render();