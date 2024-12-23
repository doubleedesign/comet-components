<?php
if (!isset($attributes) || !isset($innerComponents)) {
	return;
}

use Doubleedesign\Comet\Components\Cover;

$component = new Cover($attributes, $innerComponents);
$component->render();
