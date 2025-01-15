<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\ListItem;

$component = new ListItem($attributes, $innerComponents);
$component->render();
