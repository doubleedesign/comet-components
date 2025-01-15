<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Buttons;

$component = new Buttons($attributes, $innerComponents);
$component->render();
