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
    use BlockElementModifier;
    use Icon;

    /**
     * @var ?string $icon
     * @description Icon class name; for link-group context default value is 'fa-link', or 'fa-arrow-up-right-from-square' if target is '_blank'
     */
    protected ?string $icon;
    protected ?string $label;
    protected ?string $description;
    protected ?string $url;
    protected ?string $target;

    public function __construct(array $attributes) {
        parent::__construct($attributes, 'components.Link.link');
        $this->init_bem_structure('components.Link.link', $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->label = $attributes['label'] ?? $attributes['title'] ?? null;
        $this->description = $attributes['description'] ?? null;
        $this->url = $attributes['url'] ?? $attributes['href'] ?? '#';
        $this->target = $attributes['target'] ?? null;

        if (!isset($attributes['icon']) && $this->get_context() === 'link-group') {
            if (isset($attributes['target']) && $attributes['target'] === '_blank') {
                $attributes['icon'] = 'fa-arrow-up-right-from-square';
            }
            else {
                $attributes['icon'] = 'fa-link';
            }
        }
        $this->set_icon_from_attrs($attributes);
    }

    // Utility function to get just the link text.
    // Used by components with customised link rendering, such as Menu.
    // TODO: Maybe Menu is too tightly coupled to this and should not use this component.
    public function get_content(): string {
        $blade = BladeService::getInstance();

        return $blade->make('components.Link.partials.link-text', [
            'label'       => $this->label,
            'description' => $this->description,
            'bemPrefix'   => $this->get_bem_prefix()
        ])->render();
    }

    protected function get_html_attributes(): array {
        $attributes = array_merge(
            parent::get_html_attributes(),
            ['href' => $this->url]
        );

        if ($this->target) {
            $attributes['target'] = $this->target;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'     => $this->get_filtered_classes(),
            'attributes'  => $this->get_html_attributes(),
            'iconPrefix'  => $this->iconPrefix ?? null,
            'icon'        => $this->icon ?? null,
            'label'       => $this->label,
            'description' => $this->description,
            'bemPrefix'   => $this->get_bem_prefix() ?? ''
        ])->render();
    }
}
