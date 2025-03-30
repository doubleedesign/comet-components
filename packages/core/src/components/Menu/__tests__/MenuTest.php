<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\Menu;

beforeEach(function() {
	$this->menuItems = [
		[
			'title'           => 'Home',
			'link_attributes' => [
				'href' => '/'
			]
		],
		[
			'title'           => 'About',
			'link_attributes' => [
				'href' => '/about'
			],
		],
		[
			'title'           => 'Contact',
			'link_attributes' => [
				'href' => '/contact'
			]
		]
	];
});

test('default bem class structure', function() {
	ob_start();
	$menu = new Menu([], $this->menuItems);
	$menu->render();
	$output = ob_get_clean();

	$dom = new DOMDocument();
	@$dom->loadHTML($output);
	$wrapper = $dom->getElementsByTagName('nav')->item(0);
	$firstList = $wrapper->getElementsByTagName('ul')->item(0);
	$item = $firstList->getElementsByTagName('li')->item(0);
	$link = $item->getElementsByTagName('a')->item(0);

	expect($wrapper->className)->toEqual('menu');
	expect($firstList->className)->toEqual('menu-list');
	expect($item->className)->toEqual('menu-list__item');
	expect($link->className)->toEqual('menu-list__item__link');
});
test('context bem class structure', function() {
	ob_start();
	$menu = new Menu(['context' => 'footer'], $this->menuItems);
	$menu->render();
	$output = ob_get_clean();

	$dom = new DOMDocument();
	@$dom->loadHTML($output);
	$wrapper = $dom->getElementsByTagName('nav')->item(0);
	$firstList = $wrapper->getElementsByTagName('ul')->item(0);
	$item = $firstList->getElementsByTagName('li')->item(0);
	$link = $item->getElementsByTagName('a')->item(0);

	expect($wrapper->className)->toEqual('footer__menu');
	expect($firstList->className)->toEqual('footer__menu-list');
	expect($item->className)->toEqual('footer__menu-list__item');
	expect($link->className)->toEqual('footer__menu-list__item__link');
});
