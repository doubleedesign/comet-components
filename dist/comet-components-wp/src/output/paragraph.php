<?php
if (!isset($attributes) || !isset($content)) {
	return;
}

use Doubleedesign\Comet\Core\Paragraph;


$component = new Paragraph($attributes, $content);
$component->render();
