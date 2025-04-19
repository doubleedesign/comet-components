<?php
use Doubleedesign\Comet\Core\SiteHeader;
use Doubleedesign\Comet\Core\{Paragraph};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'breakpoint', 'classes', 'hAlign', 'icon', 'iconPrefix', 'logoUrl', 'responsiveStyle', 'size', 'submenuIcon', 'tagName', 'testId', 'vAlign'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);

$innerComponents = [new Paragraph([], 'site-header component')];

$component = new SiteHeader($attributes, $innerComponents);
$component->render();