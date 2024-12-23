<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Button;

$component = new Button($attributes, $content);
$component->render();
