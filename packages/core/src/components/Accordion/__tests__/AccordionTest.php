<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core;
use PHPUnit\Framework\TestCase;

class AccordionTest extends TestCase {
	private array $panels;

	public function __construct() {
		parent::__construct();
		$this->panels = [
			new AccordionPanel([], [
				new AccordionPanelTitle([], 'Panel 1 title'),
				new AccordionPanelContent([], [new Paragraph([], 'Panel 1 content')])
			]),
		];
	}

	function test_bem_class_structure(): void {
		ob_start();
		$component = new Accordion([], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);
		$panel = $wrapper->getElementsByTagName('details')->item(0);
		$title = $panel->getElementsByTagName('summary')->item(0);
		$content = $panel->getElementsByTagName('div')->item(0);

		$this->assertEquals('accordion', $wrapper->getAttribute('class'));
		$this->assertEquals('accordion__panel', $panel->getAttribute('class'));
		$this->assertEquals('accordion__panel__title', $title->getAttribute('class'));
		$this->assertEquals('accordion__panel__content', $content->getAttribute('class'));
	}

	function test_colour_theme_attribute() {
		ob_start();
		$component = new Accordion(['colorTheme' => 'accent'], $this->panels);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$wrapper = $dom->getElementsByTagName('div')->item(0);

		$this->assertEquals('accent', $wrapper->getAttribute('data-color-theme'));
	}
}
