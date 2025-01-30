<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Core\ButtonGroup;

$component = new ButtonGroup($attributes, $innerComponents);
$component->render();
