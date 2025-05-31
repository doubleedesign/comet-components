<?php
use Doubleedesign\Comet\Core\Table;

// Attribute keys from component JSON definition
$attributeKeys = ['allowStacking', 'caption', 'sticky'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

// $component = new Table($attributes, $innerComponents);
// $component->render();
