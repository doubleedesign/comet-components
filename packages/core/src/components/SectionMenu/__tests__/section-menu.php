<?php
use Doubleedesign\Comet\Core\SectionMenu;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['classes', 'colorTheme', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'section-menu component')];

$component = new SectionMenu($attributes, $innerComponents);
$component->render();