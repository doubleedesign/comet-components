<?php
use Doubleedesign\Comet\Core\Pullquote;

// Attribute keys from component JSON definition
$attributeKeys = ['citation', 'classes', 'colorTheme', 'textAlign', 'textColor'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
$content = "Just to be clear, comedy with the plates will not be well-received";
$attributes['citation'] = "Monica Geller, Friends";

$component = new Pullquote($attributes, $content);
$component->render();
