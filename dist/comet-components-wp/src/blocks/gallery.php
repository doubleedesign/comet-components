<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Gallery;

$component = new Gallery($attributes, $innerComponents);
$component->render();
