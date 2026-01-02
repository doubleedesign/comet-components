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
class LinkGroup extends WrappedLayoutComponent {
    use ColorTheme;
    use GroupLayoutType;

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
        $this->set_group_layout_from_attrs($attributes, GroupLayout::LIST);

        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'links';
        }

        $updatedInnerComponents = [];
        if ($attributes['heading']) {
            $this->heading = $attributes['heading'];
            array_push($updatedInnerComponents, new Heading([], $this->heading));
        }

        $linkComponents = array_map(function($link) {
            if ($link instanceof Link) {
                return $link;
            }

            return new Link([
                'context'       => $attributes['context'] ?? 'link-group',
                'label'         => $link['label'] ?? $link['title'] ?? '',
                'description'   => $link['description'] ?? null,
                'url'		         => $link['url'] ?? $link['href'] ?? '#',
                'target'        => $link['target'] ?? null,
            ]);
        }, $links);

        $groupAttrs = [
            'colorTheme'                   => $this->colorTheme->value,
            'shortName'                    => $attributes['context'] ?? 'link-group',
            'role'                         => 'group',
            'data-group-layout'            => $this->layout->value
        ];

        // TODO: This is repetitive, e.g. CardList also does this (it just has a different default)
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        array_push($updatedInnerComponents, new Group(
            $groupAttrs,
            $linkComponents
        ));

        parent::__construct($attributes, $updatedInnerComponents, 'components.LinkGroup.link-group');
    }
}
