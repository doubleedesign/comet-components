<?php
use Doubleedesign\Comet\Core\{SiteHeader, Menu, PreprocessedHTML};

// Attribute keys from component JSON definition
$attributeKeys = ['backgroundColor', 'breakpoint', 'classes', 'hAlign', 'vAlign', 'icon', 'iconPrefix', 'logoUrl', 'responsiveStyle', 'size', 'submenuIcon', 'tagName', 'testId'];
// Filter the request query vars to only those matching the above
$attributes = array_filter($_REQUEST, fn($key) => in_array($key, $attributeKeys), ARRAY_FILTER_USE_KEY);
if (!isset($attributes['logoUrl'])) {
    $attributes['logoUrl'] = 'https://doubleedesign.com.au/images/doubleedesign-logo.svg';
}

$menuComponent = new Menu([], array(
    [
        'title'           => 'Home',
        'id'              => 'menu-item-1',
        'link_attributes' => ['href' => '/'],
    ],
    [
        'title'           => 'About',
        'id'              => 'menu-item-2',
        'link_attributes' => ['href' => '/about'],
    ],
    [
        'title'           => 'Services',
        'id'              => 'menu-item-3',
		'isCurrentParent' => 'true', // example of parent item styling
        'link_attributes' => ['href' => '/services'],
        'children'        => [
            [
                'title'           => 'Service example 1',
                'id'              => 'menu-item-4',
                'link_attributes' => ['href' => '/services/example-1', 'aria-current' => 'page'], // example of current page styling
            ],
            [
                'title'           => 'Service example 2',
                'id'              => 'menu-item-5',
                'link_attributes' => ['href' => '/services/example-2'],
            ],
        ],
    ],
	[
		'title'           => 'Contact',
		'id'              => 'menu-item-6',
		'link_attributes' => ['href' => '/contact'],
	],
	[
		'title' => 'Registration',
		'id' => 'menu-item-7',
		'link_attributes' => ['href' => '/registration', 'target' => '_blank'],
	]
));

$alwaysShowExample = new PreprocessedHTML([], "<p>This is an example of content that is always shown in the header, such as contact details</p>");
$showInOverlaysExample = new PreprocessedHTML([], "<p>This is an example content shown in the overlay or off-canvas content when those modes are used and the breakpoint / viewport size triggers their use</p>");

$component = new SiteHeader($attributes, [
	'menuComponent' => $menuComponent,
	'alwaysShow' => [$alwaysShowExample],
	'showInOverlays' => [$showInOverlaysExample],
]);
$component->render();
