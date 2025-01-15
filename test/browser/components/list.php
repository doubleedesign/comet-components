<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\ListComponent;

$component = new ListComponent($attributes, $innerComponents);
$component->render();
