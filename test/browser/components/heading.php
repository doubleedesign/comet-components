<?php
// TODO: Get these from the URL
$attributes = [
    'className' => 'is-style-accent',
];
$content = 'This is a heading';

if (!isset($attributes) || !isset($content)) {
    return;
}

use Doubleedesign\Comet\Components\Heading;

$component = new Heading($attributes, $content);
$component->render();
