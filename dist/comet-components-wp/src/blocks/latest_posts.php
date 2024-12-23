<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Components\LatestPosts;

$component = new LatestPosts($attributes, $content);
$component->render();
