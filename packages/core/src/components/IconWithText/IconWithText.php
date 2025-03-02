<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconWithText extends UIComponent {
	use ColorTheme;

	protected ?string $label;
	protected string $content;

	/**
	 * @var string $iconPrefix
	 * @description Icon prefix class name
	 */
	protected string $iconPrefix = 'fa-duotone fa-solid';

	/**
	 * @var ?string $icon
	 * @description Icon class name; if not set, defaults to one matching the colour theme
	 */
	protected ?string $icon;

	function __construct(array $attributes, array $innerComponents) {
		parent::__construct($attributes, $innerComponents, 'components.IconWithText.icon-with-text');
		$this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
		$this->iconPrefix = $attributes['iconPrefix'] ?? $this->iconPrefix;
		$this->icon = $attributes['icon'] ?? null;
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
