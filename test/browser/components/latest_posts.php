<?php
if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Core\LatestPosts;

$component = new LatestPosts($attributes, $content);
$component->render();
