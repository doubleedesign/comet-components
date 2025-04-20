<?php
use Doubleedesign\Comet\Core\{Group, Heading};
use const Doubleedesign\Comet\TestUtils\MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT;

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'classes', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [
	new Heading(['level' => 3, 'classes' => 'is-style-small'], 'Group example'),
	...MOCK_INNER_COMPONENTS_BLOCK_OF_TEXT
];

$component = new Group($attributes, $innerComponents);
$component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}


