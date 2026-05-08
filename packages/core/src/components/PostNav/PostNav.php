<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::NAV])]
#[DefaultTag(Tag::NAV)]
class PostNav extends UIComponent {
    use ColorTheme;

    /**
     * @var array{href:string, content:string} $links
     * @description Links to include. Intended to be 1 or 2 only (previous and next).
     */
    protected array $links = [];

    /**
     * @var string $entityName
     * @description The name of the entity type being navigated, e.g. "Post", "Product", "Article", etc.
     */
    protected string $entityName = "Post";

    public function __construct(array $attributes) {
        $this->entityName = $attributes['entityName'] ?? $this->entityName;
        $this->set_color_theme($attributes);

        $innerComponents = [];
        if (isset($attributes['links']) && is_array($attributes['links'])) {
            foreach ($attributes['links'] as $index => $link) {
                array_push($this->links, $this->validate_link_attributes($link));
                $linkAttrs = array_merge(
                    [],
                    array_filter($link, fn($k) => !in_array($k, ['content', 'icon', 'context']))
                );

                $content = $link['content'];

                if (count($attributes['links']) <= 2) {
                    if ($index === 0 && !isset($linkAttrs['icon'])) {
                        $linkAttrs['icon'] = 'fa-arrow-left';
                        $linkAttrs['classes'] = ["post-nav__link--prev"];
                        $linkAttrs['label'] = "<span>Previous {$this->entityName}</span>" . $content;
                    }
                    if ($index === 1 && !isset($linkAttrs['icon'])) {
                        $linkAttrs['icon'] = 'fa-arrow-right';
                        $linkAttrs['classes'] = ["post-nav__link--next"];
                        $linkAttrs['label'] = "<span>Next {$this->entityName}</span>" . $content;
                    }
                }

                array_push($innerComponents, new Link($linkAttrs));
            }
        }

        parent::__construct($attributes, $innerComponents, 'components.PostNav.post-nav');
    }

    private function validate_link_attributes(array $link): array {
        return Utils::array_pick($link, ['href', 'target', 'rel', 'title', 'content', 'icon']);
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if (isset($this->colorTheme)) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'tag'        => $this->tagName->value,
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
