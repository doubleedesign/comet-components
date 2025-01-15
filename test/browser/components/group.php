<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Group;

$component = new Group($attributes, $innerComponents);
$component->render();
