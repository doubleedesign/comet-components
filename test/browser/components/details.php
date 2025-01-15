<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Details;

$component = new Details($attributes, $content);
$component->render();
