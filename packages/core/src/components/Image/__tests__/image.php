<?php
use Doubleedesign\Comet\Core\Image;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['align', 'alt', 'aspectRatio', 'caption', 'classes', 'height', 'href', 'isParallax', 'scale', 'src', 'tagName', 'testId', 'title', 'width'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'image component')];

$component = new Image($attributes, $innerComponents);
$component->render();