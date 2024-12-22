<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Components\ListItem;

$component = new ListItem($attributes, $innerComponents);
$component->render();
