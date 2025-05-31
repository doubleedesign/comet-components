<?php

namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class IconLinks extends Renderable {
    use LayoutAlignment;
    use LayoutOrientation;

    /**
     * Array of arrays with URL, label, and icon class name
     */
    protected array $links = [];

    /**
     * Class name prefix for the icons
     */
    protected ?string $iconPrefix = 'fa-brands';

    public function __construct(array $attributes, array $links) {
        parent::__construct($attributes, 'components.IconLinks.icon-links');
        $this->set_layout_alignment_from_attrs($attributes, Alignment::CENTER);
        $this->set_orientation_from_attrs($attributes, Orientation::HORIZONTAL);
        $this->links = $links;
        $this->iconPrefix = $attributes['iconPrefix'] ?? $this->iconPrefix;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => implode(' ', $this->get_filtered_classes()),
            'attributes' => $this->get_html_attributes(),
            'iconPrefix' => $this->iconPrefix,
            'items'      => $this->links,
        ])->render();
    }
}
