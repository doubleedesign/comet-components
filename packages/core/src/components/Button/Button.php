<?php
namespace Doubleedesign\Comet\Core;

/**
 * Button component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Prompt the user to take an action with a button or button-style link.
 */
#[AllowedTags([Tag::A, Tag::BUTTON])]
#[DefaultTag(Tag::A)]
class Button extends Renderable {
    use ColorTheme;

    /**
     * @var ?bool $isOutline
     * @description Whether to use outline style instead of solid/filled
     */
    protected ?bool $isOutline = false;

    /**
     * @var string|null $href
     * @description URL to link to if using <a> tag.
     */
    protected ?string $href = '';

    /**
     * @var string $content
     * @description Plain text or basic HTML
     */
    protected string $content;

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, 'components.Button.button');
        $this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
        $this->isOutline = $attributes['isOutline'] ?? false;
        $this->content = $content;
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        $result = array_merge(
            [
                'button',
                "button--{$this->colorTheme->value}",
                ...($this->isOutline ? ["button--{$this->colorTheme->value}--outline"] : []),
            ],
            $classes
        );

        return array_unique($result);
    }

    protected function get_html_attributes(): array {
        $attrs = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attrs['data-color-theme'] = $this->colorTheme->value;
        }

        return $attrs;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
        ])->render();
    }
}
