<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\Image;

$component = new Image($attributes, $content);
$component->render();
