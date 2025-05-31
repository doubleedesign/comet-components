<?php
use Doubleedesign\Comet\Core\DateRangeBlock;

// Attribute keys from component JSON definition
$attributeKeys = ['colorTheme', 'startDate', 'endDate', 'locale', 'showDay', 'showYear'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);
// Filter out any attributes that are empty or false
$attributes = array_filter($attributes, function($value) {
    return $value !== '' && $value !== 'false' && $value !== 'none' && $value !== 'null';
});

// Convert date2 from the format Storybook provides to a supported one
try {
    $attributes['startDate'] = (new DateTime($attributes['startDate']))->format('Y-m-d');
    $attributes['endDate'] = (new DateTime($attributes['endDate'])->format('Y-m-d'));
    $component = new DateRangeBlock($attributes);
    $component->render();
}
catch (Exception $e) {
}
