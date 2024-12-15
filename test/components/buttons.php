<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Components\Buttons;

$component = new Buttons($attributes, $innerComponents);
$component->render();
