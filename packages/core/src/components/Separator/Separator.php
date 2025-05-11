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
    /**
     * @var ThemeColor $color
     */
    protected ThemeColor $color = ThemeColor::PRIMARY;

    public function __construct(array $attributes) {
        $classes = $attributes['classes'] ?? [];
        if (isset($attributes['className'])) {
            $classes[] = str_replace('is-style-', 'separator--', $attributes['className']);
        }

        parent::__construct(
            array_merge($attributes, ['classes' => $classes]),
            'components.Separator.separator'
        );

        $this->color = ThemeColor::tryFrom($attributes['color'] ?? '') ?? ThemeColor::tryFrom($attributes['backgroundColor'] ?? '') ?? $this->color;
    }

    protected function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        return array_filter($classes, function($class) {
            return $class !== 'separator--default' && !str_starts_with($class, 'is-style-');
        });
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        $attributes['data-color-theme'] = $this->color->value;

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
        ])->render();
    }
}
