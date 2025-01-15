<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Core\Cover;

$component = new Cover($attributes, $innerComponents);
$component->render();
