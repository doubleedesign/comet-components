<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::DETAILS])]
#[DefaultTag(Tag::DIV)]
abstract class PanelComponent extends UIComponent {
	/** @var array<Renderable> */
	protected array $innerComponents;

	protected string $title;
	protected ?string $subtitle;

	function __construct(array $attributes, array $innerComponents, string $bladeFile) {
		parent::__construct($attributes, $innerComponents, $bladeFile);
		$this->title = Utils::sanitise_content($attributes['title'] ?? '');
		$this->subtitle = isset($attributes['subtitle']) ? Utils::sanitise_content($attributes['subtitle']) : null;
	}

	protected function get_bem_name(): ?string {
		return "{$this->context}__panel__content";
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

		// These components render their children directly without their own wrappers because that's handled by get_content() and Vue
		echo $blade->make($this->bladeFile, [
			'children' => $this->innerComponents
		])->render();
	}
}
