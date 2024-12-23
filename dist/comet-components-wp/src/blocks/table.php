<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Table;

$component = new Table($attributes, $content);
$component->render();
