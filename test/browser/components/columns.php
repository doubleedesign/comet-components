<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Components\Columns;

$component = new Columns($attributes, $innerComponents);
$component->render();
