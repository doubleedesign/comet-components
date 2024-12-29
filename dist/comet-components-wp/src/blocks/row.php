<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Row;

$component = new Row($attributes, $innerComponents);
$component->render();
