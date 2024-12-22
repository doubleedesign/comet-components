<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\Details;

$component = new Details($attributes, $content);
$component->render();
