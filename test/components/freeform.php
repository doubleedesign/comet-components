<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\Freeform;

$component = new Freeform($attributes, $content);
$component->render();
