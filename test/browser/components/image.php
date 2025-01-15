<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Image;

$component = new Image($attributes, $content);
$component->render();
