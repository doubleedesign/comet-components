<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Pullquote;

$component = new Pullquote($attributes, $content);
$component->render();
