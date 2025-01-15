<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Columns;

$component = new Columns($attributes, $innerComponents);
$component->render();
