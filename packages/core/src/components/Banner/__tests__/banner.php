<?php
use Doubleedesign\Comet\Core\{Banner, Heading, ButtonGroup};
use const Doubleedesign\Comet\TestUtils\{MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT, MOCK_INNER_COMPONENTS_BUTTONS};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'containerSize', 'contentMaxWidth', 'focalPoint', 'hAlign', 'vAlign', 'isParallax', 'maxHeight', 'minHeight', 'overlayColor', 'overlayOpacity', 'tagName'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [
    new Heading([], 'Banner example'),
    ...MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT,
    new ButtonGroup([], MOCK_INNER_COMPONENTS_BUTTONS)
];

$component = new Banner(
    [
        ...$attributes,
        'imageUrl' => 'https://cometcomponents.io/test/assets/example-image-2.jpg',
        'imageAlt' => 'View of Big Ben in London',
    ],
    $innerComponents
);
$component->render();
