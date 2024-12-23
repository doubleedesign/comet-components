<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Grid;

$component = new Grid($attributes, $content);
$component->render();
