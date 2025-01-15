<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Button;

$component = new Button($attributes, $content);
$component->render();
