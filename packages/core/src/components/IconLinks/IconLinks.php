<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconLinks extends UIComponent {
    use LayoutAlignment;
    use LayoutOrientation;

    /**
     * Array of arrays with URL, label, and icon class name
     *
     * @var array{url: string, label: string, icon: string}[] $links
     */
    protected array $links = [];

    /**
     * Class name prefix for the icons
     *
     * @var string|null
     */
    protected ?string $iconPrefix = 'fa-brands';

    public function __construct(array $attributes, array $links) {
        parent::__construct($attributes, [], 'components.IconLinks.icon-links');
        $this->init_bem_structure('components.IconLinks.icon-links', $attributes['context'] ?? null, $attributes['shortName'] ?? null);
        $this->set_layout_alignment($attributes, Alignment::CENTER);
        $this->set_orientation($attributes);
        $this->links = $links;
        $this->iconPrefix = $attributes['iconPrefix'] ?? $this->iconPrefix;
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->orientation)) {
            $attributes['data-orientation'] = $this->orientation->value;
        }

        if (isset($this->hAlign)) {
            $attributes['data-hAlign'] = $this->hAlign->value;
        }

        if (isset($this->vAlign)) {
            $attributes['data-vAlign'] = $this->vAlign->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'iconPrefix' => $this->iconPrefix,
            'items'      => $this->links,
            'itemClass'  => $this->get_bem_prefix() . '__item',
        ])->render();
    }
}
