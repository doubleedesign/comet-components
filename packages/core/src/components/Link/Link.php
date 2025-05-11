<?php
namespace Doubleedesign\Comet\Core;

/**
 * Link component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Display a link with a contextual icon.
 */
#[AllowedTags([Tag::A])]
#[DefaultTag(Tag::A)]
class Link extends Renderable {
    use Icon;

    /**
     * @var ?string $icon
     * @description Icon class name; for link-group context default value is 'fa-link', or 'fa-arrow-up-right-from-square' if target is '_blank'
     */
    protected ?string $icon;

    /**
     * @var string $content
     * @description Plain text or basic HTML
     */
    protected string $content;

    public function __construct(array $attributes, string $content) {
        parent::__construct($attributes, 'components.Link.link');
        $this->content = $content;
        $this->context = $attributes['context'] ?? null;

        if (!isset($attributes['icon']) && $this->context === 'link-group') {
            if (isset($attributes['target']) && $attributes['target'] === '_blank') {
                $attributes['icon'] = 'fa-arrow-up-right-from-square';
            }
            else {
                $attributes['icon'] = 'fa-link';
            }
        }
        $this->set_icon_from_attrs($attributes);
    }

    public function get_content(): string {
        return $this->content;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'iconPrefix' => $this->iconPrefix ?? null,
            'icon'       => $this->icon ?? null,
            'content'    => $this->content
        ])->render();
    }
}
