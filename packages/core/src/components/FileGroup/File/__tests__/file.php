<?php

use Doubleedesign\Comet\Core\File;

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'colorTheme', 'description', 'icon', 'iconPrefix', 'mimeType', 'size', 'tagName', 'testId', 'title', 'uploadDate', 'url'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$component = new File($attributes);
$component->render();
