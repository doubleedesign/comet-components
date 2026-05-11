<?php
namespace Doubleedesign\Comet\Core;

/**
 * LinkGroup component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description Render a group of Link components with a common color theme.
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class LinkGroup extends LayoutComponent {
	use BackgroundColor;
    use ColorTheme;
    use GroupLayoutType;
    use NestedState;

    /**
     * @var string|null $heading Optional heading text for the link group section
     */
    protected ?string $heading;

    /**
     * @param  array  $attributes
     * @param array<Link|array<string,string> $links - Either an array of Link objects or an array of associative arrays corresponding to Link fields
     */
    public function __construct(array $attributes, array $links) {
        $this->set_color_theme($attributes, ThemeColor::PRIMARY);
        $this->set_group_layout($attributes);
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->heading = $attributes['heading'] ?? null;

        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'links';
        }

        $groupAttrs = [
            'shortName'                    => 'link-group',
            'role'                         => 'group',
            'data-group-layout'            => $this->layout !== GroupLayout::LIST ? $this->layout->value : null, // only include if not the default
        ];
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        $links = array_map(function($link) use ($attributes) {
            if ($link instanceof Link) {
                return $link;
            }

            return new Link([
                'label'         => $link['label'] ?? $link['title'] ?? '',
                'description'   => $link['description'] ?? null,
                'url'           => $link['url'] ?? $link['href'] ?? '#',
                'target'        => $link['target'] ?? null,
            ]);
        }, $links);
        $innerContent = new Group($groupAttrs, $links);

        $updatedInnerComponents = $this->heading ? [new Heading([], $this->heading), $innerContent] : [$innerContent];

        parent::__construct($attributes, $updatedInnerComponents, 'components.LinkGroup.link-group');
    }

    protected function get_html_attributes(): array {
        $attributes = parent::get_html_attributes();

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

	    if ($this->get_background_color() !== null) {
		    $attributes['data-background'] = $this->get_background_color()->value;
	    }

	    return $attributes;
    }
}
