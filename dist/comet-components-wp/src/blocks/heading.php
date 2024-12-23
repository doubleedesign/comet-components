<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Heading;

$component = new Heading($attributes, $content);
$component->render();
