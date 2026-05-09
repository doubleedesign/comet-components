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
    use BlockElementModifier;
    use ColorTheme;

    /**
     * @var ?bool $isOutline
     * @description Whether to use outline style instead of solid/filled
     * TODO: This might be better handled as a style modifier so we can handle more styles (though what would they be?)
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
        $this->init_bem_structure('components.Button.button', $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->set_color_theme($attributes);
        $this->isOutline = $attributes['isOutline'] ?? false;
        $this->content = $content;
    }

    protected function get_html_attributes(): array {
        $attrs = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attrs['data-color-theme'] = $this->colorTheme->value;
        }
        if ($this->isOutline) {
            $attrs['data-style'] = 'outline';
        }

        return $attrs;
    }

	public function get_filtered_classes(): array {
		return array_unique(
			array_merge(
				[$this->get_shortname()],
				$this->get_bem_classes(),
				$this->classes
			)
		);
	}
    public function get_content(): string {
        return $this->content;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'content'    => Utils::sanitise_content($this->content, Settings::INLINE_PHRASING_ELEMENTS),
        ])->render();
    }
}
