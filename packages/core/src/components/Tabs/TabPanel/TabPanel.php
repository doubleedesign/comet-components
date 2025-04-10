<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class TabPanel extends UIComponent {
	/** @var array<Renderable> */
	protected array $innerComponents;

	protected string $title;
	protected ?string $subtitle;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct(
			array_merge($attributes, ['context' => 'tabs__content']),
			$innerComponents,
			'components.Tabs.TabPanel.tab-panel'
		);
		$this->title = Utils::sanitise_content($attributes['title'] ?? '');
		$this->subtitle = Utils::sanitise_content($attributes['subtitle'] ?? null);
	}

	public function get_title(): ?array {
		return array(
			'attributes' => [],
			'classes' => ['tabs__tab-list__item'],
			'content' => "$this->title" . ($this->subtitle ? "<small class='tabs__tab-list__item__subtitle'>$this->subtitle</small>" : ''),
		);
	}

	public function get_content(): ?array {
		ob_start();
		$this->render();
		$content = ob_get_clean();

		return array(
			'attributes' => $this->get_html_attributes(),
			'classes' => $this->get_filtered_classes(),
			'content' => trim($content),
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		// This component renders the children directly without its own wrapper because that's handled by get_content() and Vue
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents
		])->render();
	}
}
