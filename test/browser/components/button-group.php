<?php
use Doubleedesign\Comet\Core\ButtonGroup;
use Doubleedesign\Comet\Core\Button;

$attributes = [];
$innerComponents = [
	new Button(['href' => '#', 'colorTheme' => 'primary'], 'Button 1'),
	new Button(['href' => '#', 'colorTheme' => 'primary'], 'Button 2'),
];

if(isset($_REQUEST['hAlign'])) {
	$attributes['justifyContent'] = $_REQUEST['hAlign'];
}
if(isset($_REQUEST['orientation'])) {
	$attributes['orientation'] = $_REQUEST['orientation'];
}
if(isset($_REQUEST['tagName'])) {
	$attributes['tagName'] = $_REQUEST['tagName'];
}

$component = new ButtonGroup($attributes, $innerComponents);
$component->render();
