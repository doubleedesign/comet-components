<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Quote;

$component = new Quote($attributes, $content);
$component->render();
