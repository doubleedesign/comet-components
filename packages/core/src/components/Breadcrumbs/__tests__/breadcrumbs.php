<?php
use Doubleedesign\Comet\Core\Breadcrumbs;

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$breadcrumbs = [
    [
        'title'   => 'Home',
        'url'     => 'https://storybook.comet-components.test:6006',
        'current' => false,
    ],
    [
        'title'   => 'Lorem ipsum',
        'url'     => '#',
        'current' => false,
    ],
    [
        'title'   => 'Example current page',
        'url'     => '#',
        'current' => true,
    ],
];

$component = new Breadcrumbs($attributes, $breadcrumbs);
$component->render();
