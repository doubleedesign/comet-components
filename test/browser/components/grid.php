<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Grid;

$component = new Grid($attributes, $content);
$component->render();
