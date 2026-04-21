<?php
namespace Doubleedesign\Comet\Core;

/**
 * Separator component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Visually separate content with a styled horizontal line.
 */
#[AllowedTags([Tag::HR])]
#[DefaultTag(Tag::HR)]
class Separator extends Renderable {
    use BlockElementModifier;
    use ColorTheme;
    use LayoutContainerSize;
    use NestedState;
    protected string $lineStyle = '';

    /**
     * @var ThemeColor $color
     */
    protected ThemeColor $color = ThemeColor::PRIMARY;

    public function __construct(array $attributes) {
        parent::__construct($attributes, 'components.Separator.separator');
        $this->set_color_theme($attributes, ThemeColor::PRIMARY);
        $this->set_size($attributes);
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->init_bem_structure($this->bladeFile);
        $this->lineStyle = isset($attributes['lineStyle']) ? $attributes['lineStyle'] : (is_string($attributes['style']) ? $attributes['style'] : '');
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (!$this->isNested && isset($this->size)) {
            $attributes['data-size'] = $this->size->value;
        }

        if ($this->lineStyle) {
            $attributes['data-style'] = $this->lineStyle;
        }

        return $attributes;
    }

    protected function get_inline_styles(): array {
        $styles = parent::get_inline_styles();

        if ($this->colorTheme) {
            $styles['--theme-color'] = "var(--color-{$this->colorTheme->value})";
        }

        $styles['max-width'] = $this->size ? "var(--width-{$this->size->value})" : 'var(--width-contained)';

        return $styles;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
        ])->render();
    }
}
