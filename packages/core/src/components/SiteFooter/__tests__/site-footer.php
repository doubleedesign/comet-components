<?php
use Doubleedesign\Comet\Core\SiteFooter;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['size', 'backgroundColor', 'tagName', 'classes'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'site-footer component')];

$component = new SiteFooter($attributes, $innerComponents);
$component->render();