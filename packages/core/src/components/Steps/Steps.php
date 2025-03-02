<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::OL])]
#[DefaultTag(Tag::OL)]
class Steps extends LayoutComponent {
	use LayoutOrientation;
	use ColorTheme;

	/**
	 * @var array<Step> $innerComponents
	 */
	protected array $innerComponents;
	/**
	 * @var int|null $maxPerRow
	 * @description The maximum number of steps to display per row when orientation is horizontal
	 */
	protected ?int $maxPerRow = null;
	/**
	 * @var array<string> $classes
	 * @description CSS classes
	 * @supported-values is-style-numbered, is-style-simple
	 */
	protected array|null $classes;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Steps.steps');
		$this->set_orientation_from_attrs($attributes);
		$this->set_color_theme_from_attrs($attributes);
		if($this->orientation === Orientation::HORIZONTAL) {
			$this->maxPerRow = isset($attributes['maxPerRow']) ? intval($attributes['maxPerRow']) : 3;
		}
		else {
			$this->maxPerRow = null;
		}
	}

	function get_html_attributes(): array {
		$attributes = array_merge(
			parent::get_html_attributes(),
			['data-orientation' => $this->orientation->value]
		);

		if($this->orientation === Orientation::HORIZONTAL && isset($this->maxPerRow)) {
			$attributes['data-max-per-row'] = $this->maxPerRow;
		}

		if(isset($this->colorTheme)) {
			$attributes['data-color-theme'] = $this->colorTheme->value;
		}

		return $attributes;
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
