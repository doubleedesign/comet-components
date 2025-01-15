<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Column;

$component = new Column($attributes, $innerComponents);
$component->render();
