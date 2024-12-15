<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\SimpleComponent;

$component = new SimpleComponent($attributes, $content);
$component->render();
