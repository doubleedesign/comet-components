<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Group;

$component = new Group($attributes, $innerComponents);
$component->render();
