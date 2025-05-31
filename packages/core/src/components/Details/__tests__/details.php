<?php

use const Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT;
use Doubleedesign\Comet\Core\Details;

// Attribute keys from component JSON definition
$attributeKeys = ['summary'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT;

$component = new Details($attributes, $innerComponents);
$component->render();
