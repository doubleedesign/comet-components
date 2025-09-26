<?php
namespace Doubleedesign\Comet\Core;

/**
 * LinkGroup component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a group of Link components with a common color theme.
 */
#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class LinkGroup extends UIComponent {
    use ColorTheme;

    /**
     * @var string|null $heading Optional heading text for the link group section
     */
    protected ?string $heading;

    /**
     * @param  array  $attributes
     * @param array<Link|array<string,string> $links - Either an array of Link objects or an array of associative arrays corresponding to Link fields
     */
    public function __construct(array $attributes, array $links) {
        $this->set_color_theme_from_attrs($attributes, ThemeColor::INFO);
        $innerComponents = [];

        if ($attributes['heading']) {
            $this->heading = $attributes['heading'];
            array_push($innerComponents, new Heading(['level' => 2], $this->heading));
            unset($attributes['heading']);
        }

        $innerComponents = array_merge(
            $innerComponents,
            array_map(function($link) {
                if ($link instanceof Link) {
                    return $link;
                }

                return new Link(
                    array_merge($link['attributes'], ['context' => 'link-group']),
                    $link['content']
                );
            }, $links)
        );

        parent::__construct($attributes, $innerComponents, 'components.LinkGroup.link-group');
    }

    protected function get_html_attributes(): array {
        return array_merge(
            parent::get_html_attributes(),
            ['data-color-theme' => $this->colorTheme->value]
        );
    }

    public function render(): void {
        $blade = BladeService::getInstance();

        echo $blade->make($this->bladeFile, [
            'classes'    => $this->get_filtered_classes(),
            'attributes' => $this->get_html_attributes(),
            'children'   => $this->innerComponents
        ])->render();
    }
}
