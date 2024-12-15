<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\WrapperComponent;

$component = new WrapperComponent($attributes, $content);
$component->render();
