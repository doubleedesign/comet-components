<?php
use Doubleedesign\Comet\Core\Breadcrumbs;
use Doubleedesign\Comet\Core\{Paragraph};

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

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}

