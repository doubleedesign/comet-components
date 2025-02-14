<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([...Settings::BLOCK_PHRASING_ELEMENTS, ...Settings::INLINE_PHRASING_ELEMENTS])]
#[DefaultTag(Tag::SPAN)]
abstract class TextElement extends Renderable {

	/**
	 * @var string $content
	 * @description Plain text or basic HTML
	 */
	protected string $content;
	/**
	 * @var Alignment|null $textAlign
	 */
	protected ?Alignment $textAlign = Alignment::MATCH_PARENT;

	function __construct(array $attributes, string $content, string $bladeFile) {
		parent::__construct($attributes, $bladeFile);
		$this->content = $content;
		$this->textAlign = isset($attributes['textAlign']) ? Alignment::tryFrom($attributes['textAlign']) : null;
	}

	/**
	 * Collect the inline styles using the relevant supported attributes
	 *
	 * @return array<string, string>
	 */
	function get_inline_styles(): array {
		$styles = [];

		if ($this->textAlign) {
			$styles['text-align'] = $this->textAlign->value;
		}

		return $styles;
	}

	/**
	 * Get the valid/supported classes for this component
	 * @return array<string>
	 */
	function get_filtered_classes(): array {
		$current_classes = parent::get_filtered_classes();
		$bem_name = $this->get_bem_name();

		// I want the BEM name in Renderable so it gets applied to all other component types,
		// and text elements with explicit context,
		// but don't want it for most basic text elements like headings and paragraphs
		if (!$this->context) {
			unset($current_classes[array_search($bem_name, $current_classes)]);
		}

		return $current_classes;
	}


	/**
	 * Default render method (child classes may override this)
	 * @return void
	 */
	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
		])->render();
	}
}
