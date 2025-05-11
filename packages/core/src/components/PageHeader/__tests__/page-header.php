<?php
use Doubleedesign\Comet\Core\PageHeader;

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'size', 'title'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$breadcrumbs = [
    [
        'title'   => 'Home',
        'url'     => 'https://storybook.comet-components.test:6006',
        'current' => false,
    ],
    [
        'title'   => 'Page header example',
        'url'     => '#',
        'current' => true,
    ],
];

$component = new PageHeader($attributes, 'Page header example', $breadcrumbs);
$component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if (getenv('SERVER_NAME') === 'comet-components.test') {
    require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}
