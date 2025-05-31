<?php
use Doubleedesign\Comet\Core\Image;

// Attribute keys from component JSON definition
$attributeKeys = ['align', 'alt', 'aspectRatio', 'caption', 'classes', 'height', 'width', 'href', 'isParallax', 'scale', 'title'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);
// Filter out "none" and empty values
$attributes = array_filter($attributes, fn($value) => $value !== 'none' && $value !== '');
// Make classes an array if passed
if (isset($attributes['classes'])) {
    $attributes['classes'] = array_map('trim', explode(' ', $attributes['classes']));
}

$component = new Image([
    ...$attributes,
    'src' => 'https://cometcomponents.io/test/assets/example-image-1.jpg',
]);
$component->render();
