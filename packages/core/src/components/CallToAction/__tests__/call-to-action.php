<?php
use Doubleedesign\Comet\Core\CallToAction;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'call-to-action component')];

$component = new CallToAction($attributes, $innerComponents);
$component->render();