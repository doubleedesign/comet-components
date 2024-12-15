<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\Paragraph;

$component = new Paragraph($attributes, $content);
$component->render();
