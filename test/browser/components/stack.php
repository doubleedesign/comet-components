<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Core\Stack;

$component = new Stack($attributes, $innerComponents);
$component->render();
