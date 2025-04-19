<?php
use Doubleedesign\Comet\Core\Banner;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'containerSize', 'contentMaxWidth', 'focalPoint', 'hAlign', 'imageAlt', 'imageUrl', 'isParallax', 'maxHeight', 'minHeight', 'overlayColor', 'overlayOpacity', 'tagName', 'testId', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'banner component')];

$component = new Banner($attributes, $innerComponents);
$component->render();