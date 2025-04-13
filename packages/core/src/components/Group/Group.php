<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION, Tag::ARTICLE, Tag::ASIDE])]
#[DefaultTag(Tag::DIV)]
class Group extends UIComponent {
	use BackgroundColor;

	/**
	 * @var ?array<string> $classes
	 * @supported-values group, group--breakout
	 */
	protected ?array $classes;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.Group.group');
		$this->set_background_color_from_attrs($attributes);
		$this->simplify_all_background_colors();
		// Allow something other than "group" to be used as the shortName, primarily for automatic BEM class naming
		$this->shortName = $attributes['shortName'] ?? $this->shortName;
	}

	protected function get_filtered_classes(): array {
		if($this->context) {
			return parent::get_filtered_classes();
		}

		return array_merge(parent::get_filtered_classes(), ['layout-block']);
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->backgroundColor)) {
			$attributes['data-background'] = $this->backgroundColor->value;
		}
		else if(isset($this->gradient)) {
			$attributes['data-background'] = 'gradient-' . $this->gradient;
		}

		return $attributes;
	}

	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'tag'        => $this->tagName->value,
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
