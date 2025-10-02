<?php
use Doubleedesign\Comet\Core\ContentImageAdvanced;

$attributeKeys = ['tagName', 'alt', 'caption', 'title', 'src', 'align', 'aspectRatio'];
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
$attributes = array_map(fn($value) => $value === 'true' ? true : ($value === 'false' ? false : $value), $attributes);

$attributes['focalPoint'] = [
    'x' => isset($_REQUEST['focalPointX']) ? (int)$_REQUEST['focalPointX'] : 50,
    'y' => isset($_REQUEST['focalPointY']) ? (int)$_REQUEST['focalPointY'] : 50,
];
$attributes['offset'] = [
    'x' => isset($_REQUEST['offsetX']) ? (int)$_REQUEST['offsetX'] : 0,
    'y' => isset($_REQUEST['offsetY']) ? (int)$_REQUEST['offsetY'] : 0,
];

$component = new ContentImageAdvanced($attributes);
$component->render();
