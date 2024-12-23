<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Stack;

$component = new Stack($attributes, $content);
$component->render();
