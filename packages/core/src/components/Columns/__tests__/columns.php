<?php
use Doubleedesign\Comet\Core\{Columns, Column, Paragraph};

$innerComponents = $_REQUEST['innerComponents'] ?? null;
// Attribute keys from component JSON definition
$attributeKeys = ['allowStacking', 'backgroundColor', 'hAlign', 'tagName', 'testId', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
// Filter out any attributes that are empty or false
$attributes = array_filter($attributes, function($value) {
	return $value !== '' && $value !== 'false' && $value !== 'none' && $value !== 'null';
});

$innerColumns = [
	new Column([], [new Paragraph([], 'Look at me! I\'m Chandler! Could I BE wearing any more clothes?')]),
	new Column([], [new Paragraph([], 'Be sleepy! And grumpy! Stop naming dwarves!')]),
	new Column(['backgroundColor' => 'light'], [
		new Paragraph([], 'The messers become the messees. Fried chicken could be fricken. Three bathrooms in this place, and I threw up in a coat closet. Gum would be perfection. Well, the fridge broke, so I had to eat everything. Paper! Snow! A ghost! I understand why Superman is here, but why is there a porcupine at the Easter Bunny\'s funeral?')
	]),
];

if($innerComponents) {
	$innerComponents = json_decode($innerComponents, true);
	$innerColumns = array_map(function($item) {
		return new Column($item['attributes'], [
			new Paragraph([], $item['content'])
		]);
	}, $innerComponents);
}

$component = new Columns($attributes, $innerColumns);
$component->render();

// Workaround for wrapper-close not loading from php.ini in Laravel Herd
if(getEnv('SERVER_NAME') === 'comet-components.test') {
	require_once dirname(__DIR__, 6) . '/test/browser/wrapper-close.php';
}

