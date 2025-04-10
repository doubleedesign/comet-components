<?php
namespace Doubleedesign\Comet\Core;

// Note: This component's tag changes responsively,
// determined by the Vue component that renders within ResponsivePanels
#[AllowedTags([Tag::DIV, Tag::DETAILS])]
#[DefaultTag(Tag::DIV)]
class ResponsivePanel extends UIComponent {
	/** @var array<Renderable> */
	protected array $innerComponents;

	protected string $title;
	protected ?string $subtitle;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.ResponsivePanels.ResponsivePanel.responsive-panel');
		$this->title = Utils::sanitise_content($attributes['title'] ?? '');
		$this->subtitle = Utils::sanitise_content($attributes['subtitle'] ?? null);
	}

	protected function get_bem_name(): string {
		return 'responsive-panel__content';
	}

	public function get_title(): ?array {
		return array(
			'attributes' => [],
			'classes'    => ['responsive-panel__title'],
			'content'    => $this->title . ($this->subtitle ? "<small class='responsive-panel__title__subtitle'>$this->subtitle</small>" : ''),
		);
	}

	public function get_content(): ?array {
		ob_start();
		$this->render();
		$content = ob_get_clean();

		return array(
			'attributes' => $this->get_html_attributes(),
			'classes'    => $this->get_filtered_classes(),
			'content'    => trim($content),
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
