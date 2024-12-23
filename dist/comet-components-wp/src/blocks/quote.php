<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Quote;

$component = new Quote($attributes, $content);
$component->render();
