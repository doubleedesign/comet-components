<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\Embed;

$component = new Embed($attributes, $content);
$component->render();
