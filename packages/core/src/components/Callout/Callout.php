<?php
namespace Doubleedesign\Comet\Core;

/**
 * Callout component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Highlight an important message in a callout box.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class Callout extends UIComponent {
    use ColorTheme;
    use Icon;

    /**
     * @var ?string $icon
     * @description Icon class name; default value set for success, warning, error, and info color themes
     */
    protected ?string $icon;

    /**
     * @var array<Heading|ListComponent|Paragraph|ButtonGroup|Button|Separator> $innerComponents
     */
    protected array $innerComponents;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.Callout.callout');
        $this->set_color_theme($attributes);

        if (!isset($attributes['icon'])) {
            $attributes['icon'] = match ($this->colorTheme->value) {
                'success' => 'fa-circle-check',
                'warning' => 'fa-triangle-exclamation',
                'error'   => 'fa-circle-exclamation',
                'info'    => 'fa-circle-info',
                default   => null,
            };
        }
        $this->set_icon_from_attrs($attributes);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'iconPrefix' => $this->iconPrefix,
            'icon'       => $this->icon,
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
