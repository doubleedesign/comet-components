<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Gallery;

$component = new Gallery($attributes, $innerComponents);
$component->render();
