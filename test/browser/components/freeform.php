<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Freeform;

$component = new Freeform($attributes, $content);
$component->render();
