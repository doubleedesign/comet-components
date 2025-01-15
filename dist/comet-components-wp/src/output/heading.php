<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Core\Heading;

$component = new Heading($attributes, $content);
$component->render();
