<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Stack;

$component = new Stack($attributes, $innerComponents);
$component->render();
