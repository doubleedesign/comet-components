<?php

use Doubleedesign\Comet\Core\{Column, Columns, PreprocessedHTML};
use Doubleedesign\Comet\TestUtils\MockContent;

// Attribute keys from component JSON definition
$attributeKeys = ['size', 'isNested', 'shortName', 'allowStacking', 'backgroundColor', 'hAlign', 'vAlign', 'tagName', 'testId', 'count'];
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
$count = $attributes['count'] ?? 3;
unset($attributes['count']);
$innerColumns = [];
$i = 0;
$lengths = ['short', 'medium', 'long'];
while ($i < $count) {
    $innerAttrs = [];
    // Assign a random background colour to every second column
    if ($i % 2 === 0) {
        $innerAttrs['backgroundColor'] = MockContent::get_random_background_colour();
    }

    $innerColumns[] = new Column(
        $innerAttrs,
        [new PreprocessedHTML([], MockContent::generate_paragraph($lengths[$i % count($lengths)]))],
    );
    $i++;
}

$component = new Columns($attributes, $innerColumns);
$component->render();
