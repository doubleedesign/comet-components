<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Callout extends UIComponent {
	use ColorTheme;

	/**
	 * @var string $iconPrefix
	 * @description Icon prefix class name
	 */
	protected string $iconPrefix = 'fa-solid';

	/**
	 * @var ?string $icon
	 * @description Icon class name; if not set, defaults to one matching the colour theme
	 */
	protected ?string $icon;

    function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Callout.callout');
	    $this->set_color_theme_from_attrs($attributes, ThemeColor::INFO);

		if(isset($attributes['icon'])) {
			$this->icon = $attributes['icon']; // TODO: Sanitise/validate input
		}
		else {
			$this->icon = match ($this->colorTheme->value) {
				'success' => 'fa-circle-check',
				'warning' => 'fa-triangle-exclamation',
				'error' => 'fa-circle-exclamation',
				'info' => 'fa-circle-info',
				default => null,
			};
		}
    }

	function get_filtered_classes(): array {
		$classes = parent::get_filtered_classes();

		if($this->colorTheme) {
			array_push($classes, "callout--{$this->colorTheme->value}");
		}

		return $classes;
	}

	public function render(): void {
		$blade = BladeService::getInstance();

		echo $blade->make($this->bladeFile, [
			'classes'    => implode(' ', $this->get_filtered_classes()),
			'iconPrefix' => $this->iconPrefix,
			'icon'       => $this->icon,
			'attributes' => $this->get_html_attributes(),
			'children'   => $this->innerComponents
		])->render();
	}
}
