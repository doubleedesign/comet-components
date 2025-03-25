<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconWithText extends UIComponent {
	use ColorTheme;
	use Icon;

	protected ?string $label;
	protected string $content;
	protected string $iconPrefix = 'fa-duotone fa-solid';

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.IconWithText.icon-with-text');
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
		$this->set_icon_from_attrs([
			'iconPrefix' => $attributes['iconPrefix'] ?? $this->iconPrefix,
			...$attributes
		]);
	}

	function get_html_attributes(): array {
		return array_merge(
			parent::get_html_attributes(),
			['data-color-theme' => $this->colorTheme->value]
		);
	}

	function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => $this->get_filtered_classes_string(),
			'attributes' => $this->get_html_attributes(),
			'iconPrefix' => $this->iconPrefix,
			'icon'       => $this->icon,
			'children'   => $this->innerComponents
		])->render();
	}
}
