<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\Table;

$component = new Table($attributes, $content);
$component->render();
