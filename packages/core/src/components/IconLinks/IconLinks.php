<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconLinks extends Renderable {
	use LayoutOrientation;
	use LayoutAlignment;

	/**
	 * Array of arrays with URL, label, and icon class name
	 * @var array
	 */
	protected array $links = [];

	/**
	 * Class name prefix for the icons
	 * @var string|null
	 */
	protected ?string $iconPrefix = 'fa-brands';

	function __construct(array $attributes, array $links) {
		parent::__construct($attributes, 'components.IconLinks.icon-links');
		$this->set_layout_alignment_from_attrs($attributes, Alignment::CENTER);
		$this->set_orientation_from_attrs($attributes, Orientation::HORIZONTAL);
		$this->links = $links;
		$this->iconPrefix = $attributes['iconPrefix'] ?? $this->iconPrefix;
	}

	protected function get_html_attributes(): array {
		$attributes = parent::get_html_attributes();

		if(isset($this->orientation)) {
			$attributes['data-orientation'] = $this->orientation->value;
		}

		if(isset($this->hAlign)) {
			$attributes['data-hAlign'] = $this->hAlign->value;
		}

		if(isset($this->vAlign)) {
			$attributes['data-vAlign'] = $this->vAlign->value;
		}

		return $attributes;
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'attributes' => $this->get_html_attributes(),
			'iconPrefix' => $this->iconPrefix,
			'items'      => $this->links
		])->render();
	}
}
