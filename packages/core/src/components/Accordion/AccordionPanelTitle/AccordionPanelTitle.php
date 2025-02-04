<?php
namespace Doubleedesign\Comet\Core;

class AccordionPanelTitle extends TextElement {
	use HasAllowedTags;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::SUMMARY];
	}

	function __construct(array $attributes, string $content) {
		parent::__construct(
			array_merge($attributes, ['context' => 'accordion__panel']),
			$content, '
			components.Accordion.AccordionPanelTitle.accordion-panel-title'
		);
		$this->tagName = Tag::SUMMARY;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
