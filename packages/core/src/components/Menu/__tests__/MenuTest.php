<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\Menu;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {
	private array $menuItems;

	public function __construct() {
		parent::__construct();
		$this->menuItems = [
			[
				'title' => 'Home',
				'link_attributes' => [
					'href' => '/'
				]
			],
			[
				'title' => 'About',
				'link_attributes' => [
					'href' => '/about'
				],
			],
			[
				'title' => 'Contact',
				'link_attributes' => [
					'href' => '/contact'
				]
			]
		];
	}

	public function test_default_bem_class_structure(): void {
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

		$this->assertEquals('menu', $wrapper->className);
		$this->assertEquals('menu-list', $firstList->className);
		$this->assertEquals('menu-list__item', $item->className);
		$this->assertEquals('menu-list__item__link', $link->className);
	}

	public function test_context_bem_class_structure(): void {
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

		$this->assertEquals('footer__menu', $wrapper->className);
		$this->assertEquals('footer__menu-list', $firstList->className);
		$this->assertEquals('footer__menu-list__item', $item->className);
		$this->assertEquals('footer__menu-list__item__link', $link->className);
	}
}
