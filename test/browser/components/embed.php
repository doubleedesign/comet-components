<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Embed;

$component = new Embed($attributes, $content);
$component->render();
