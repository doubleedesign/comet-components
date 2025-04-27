<?php
use Doubleedesign\Comet\Core\DateBlock;

// Attribute keys from component JSON definition
$attributeKeys = ['date', 'locale', 'showDay', 'showYear'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);
// Filter out any attributes that are empty or false
$attributes = array_filter($attributes, function($value) {
	return $value !== '' && $value !== 'false' && $value !== 'none' && $value !== 'null';
});

// Convert date from the format Storybook provides to a supported one
try {
	$attributes['date'] = new DateTime($attributes['date']);
	$component = new DateBlock($attributes);
	$component->render();
}
catch(Exception $e) {
}

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}
