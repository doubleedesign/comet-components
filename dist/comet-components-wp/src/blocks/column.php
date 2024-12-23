<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Column;

$component = new Column($attributes, $innerComponents);
$component->render();
