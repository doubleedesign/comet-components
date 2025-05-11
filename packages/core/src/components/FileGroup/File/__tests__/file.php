<?php
use Doubleedesign\Comet\Core\File;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'colorTheme', 'description', 'icon', 'iconPrefix', 'mimeType', 'size', 'tagName', 'testId', 'title', 'uploadDate', 'url'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'file component')];

$component = new File($attributes, $innerComponents);
$component->render();
