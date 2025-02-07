<?php
namespace Doubleedesign\Comet\Core;

class CallToAction extends UIComponent {
	use HasAllowedTags;

	protected ?ThemeColor $backgroundColor;
	/** @var array<Heading|Paragraph|ButtonGroup> */
	protected array $innerComponents;

	/**
	 * Specify allowed Tags using the HasAllowedTags trait
	 * @return array<Tag>
	 */
	protected static function get_allowed_wrapping_tags(): array {
		return [Tag::DIV, Tag::SECTION, Tag::ASIDE];
	}

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.CallToAction.call-to-action');
		$this->backgroundColor = isset($attributes['backgroundColor']) ? ThemeColor::tryFrom($attributes['backgroundColor']) : null;

	}

	protected function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if (isset($this->backgroundColor)) {
			$classes[] = 'bg-' . $this->backgroundColor->value;
		}

		return $classes;
	}

	function get_inline_styles(): array {
		return [];
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
