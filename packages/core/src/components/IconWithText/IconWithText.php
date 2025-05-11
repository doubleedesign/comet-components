<?php
namespace Doubleedesign\Comet\Core;

/**
 * IconWithText component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display an icon with an associated text label.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconWithText extends UIComponent {
    use ColorTheme;
    use Icon;
    protected ?string $label;
    protected string $content;

    public function __construct(array $attributes, array $innerComponents) {
        parent::__construct($attributes, $innerComponents, 'components.IconWithText.icon-with-text');
        $this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
        $this->set_icon_from_attrs([
            'iconPrefix' => $attributes['iconPrefix'] ?? 'fa-duotone fa-solid',
            ...$attributes
        ]);
    }

    public function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            ['data-color-theme' => $this->colorTheme->value]
        );
    }

    public function render(): void {
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
