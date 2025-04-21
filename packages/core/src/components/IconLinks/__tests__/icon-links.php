<?php
use Doubleedesign\Comet\Core\IconLinks;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['hAlign', 'iconPrefix', 'links', 'orientation', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Make true and false strings proper booleans
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);
// Filter out any attributes that are empty or false
$attributes = array_filter($attributes, function($value) {
	return $value !== '' && $value !== 'false' && $value !== 'none' && $value !== 'null';
});

$links = array(
	[
		"label" => "GitHub",
		"icon"  => "fa-github",
		"url"   => "https://github.com/doubleedesign/comet-components"
	],
	[
		"label" => "npm",
		"icon"  => "fa-npm",
		"url"   => "https://www.npmjs.com/~doubleedesign"
	],
	[
		"label" => "Facebook",
		"icon"  => "fa-facebook",
		"url"   => "https://www.facebook.com/doubleedesign"
	]
);

$component = new IconLinks($attributes, $links);
$component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}

