<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core;

$component = new MediaText($attributes, $innerComponents);
$component->render();
