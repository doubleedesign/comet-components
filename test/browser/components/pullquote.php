<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Pullquote;

$component = new Pullquote($attributes, $content);
$component->render();
