<?php
if (!isset($attributes) || !isset($innerComponents)) {
    return;
}

use Doubleedesign\Comet\Components\MediaText;

$component = new MediaText($attributes, $innerComponents);
$component->render();
