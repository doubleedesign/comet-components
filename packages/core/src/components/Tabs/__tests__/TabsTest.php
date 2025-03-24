<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\{Paragraph, TabPanel, TabPanelContent, TabPanelTitle, Tabs};
use PHPUnit\Framework\TestCase;

class TabsTest extends TestCase {
	private array $panels;

	public function __construct() {
		parent::__construct();
		$this->panels = [
			new TabPanel([], [
				new TabPanelTitle([], 'Panel 1 title'),
				new TabPanelContent([], [new Paragraph([], 'Panel 1 content')])
			]),
		];
	}

	function test_bem_class_structure(): void {
		ob_start();
		$component = new Tabs([], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);
		$tabList = $wrapper->getElementsByTagName('ul')->item(0);
		$tabListItem = $tabList->getElementsByTagName('li')->item(0);
		$tabContentWrapper = $wrapper->getElementsByTagName('div')->item(0);
		$tabContentItem = $tabContentWrapper->getElementsByTagName('div')->item(0);

		$this->assertEquals('tabs', $wrapper->getAttribute('class'));
		$this->assertEquals('tabs__tab-list', $tabList->getAttribute('class'));
		$this->assertEquals('tabs__tab-list__item', $tabListItem->getAttribute('class'));
		$this->assertContains('tabs__content', explode(' ', $tabContentWrapper->getAttribute('class')));
		$this->assertEquals('tabs__content__tab-panel', $tabContentItem->getAttribute('class'));
	}

	function test_role_attributes() {
		ob_start();
		$component = new Tabs([], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);
		$tabList = $wrapper->getElementsByTagName('ul')->item(0);
		$tabListItemLink = $tabList->getElementsByTagName('a')->item(0);
		$tabContentWrapper = $wrapper->getElementsByTagName('div')->item(0);
		$tabContentItem = $tabContentWrapper->getElementsByTagName('div')->item(0);

		$this->assertEquals('tablist', $tabList->getAttribute('role'));
		$this->assertEquals('tab', $tabListItemLink->getAttribute('role'));
		$this->assertEquals('tabpanel', $tabContentItem->getAttribute('role'));
	}

	function test_orientation_attribute() {
		ob_start();
		$component = new Tabs(['orientation' => 'vertical'], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);

		$this->assertEquals('vertical', $wrapper->getAttribute('data-orientation'));
	}

	function test_colour_theme_attribute() {
		ob_start();
		$component = new Tabs(['colorTheme' => 'secondary'], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);

		$this->assertEquals('secondary', $wrapper->getAttribute('data-color-theme'));
	}
}
