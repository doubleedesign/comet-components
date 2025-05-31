<?php

use Doubleedesign\Comet\Core\Separator;

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'color', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$component = new Separator($attributes);
$component->render();
