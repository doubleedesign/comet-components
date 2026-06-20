<?php

use Doubleedesign\Comet\Core\{Column, Columns, PreprocessedHTML};
use Doubleedesign\Comet\TestUtils\MockContent;

// Attribute keys from component JSON definition
$attributeKeys = ['size', 'isNested', 'shortName', 'allowStacking', 'backgroundColor', 'hAlign', 'vAlign', 'tagName', 'testId', 'qty', '_actualQty', 'columnLayout'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Filter out any attributes that are empty
$attributes = array_filter($attributes, function($value) {
    return $value !== '' && $value !== 'none' && $value !== 'null';
});
// If an attribute value is "false" make sure it is interpreted as a boolean (string "false" will go through as true otherwise)
if (isset($attributes['isNested']) && $attributes['isNested'] === 'false') {
    $attributes['isNested'] = false;
}
if (isset($attributes['allowStacking']) && $attributes['allowStacking'] === 'false') {
    $attributes['allowStacking'] = false;
}

// Generate inner components
$count = $attributes['_actualQty'] ?? 3;
unset($attributes['_actualQty']); // this is a private attribute used here only for demonstration; in a real scenario it is dynamically calculated
$innerColumns = [];
$i = 0;
$lengths = ['short', 'medium', 'long'];
while ($i < $count) {
    $innerAttrs = [];
    $innerColumns[] = new Column(
        $innerAttrs,
        [new PreprocessedHTML([], MockContent::generate_paragraph($lengths[$i % count($lengths)]))],
    );
    $i++;
}

$component = new Columns($attributes, $innerColumns);
$component->render();
