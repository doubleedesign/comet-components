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
        $this->set_color_theme_from_attrs($attributes, ThemeColor::PRIMARY);
        $this->set_group_layout_from_attrs($attributes, GroupLayout::LIST);
        $this->heading = $attributes['heading'] ?? null;

        if (!isset($attributes['shortName'])) {
            $attributes['shortName'] = 'links';
        }

        $groupAttrs = [
            'colorTheme'                   => $this->colorTheme->value ?? null, // only include if set explicitly
            'shortName'                    => 'link-group',
            'role'                         => 'group',
            'data-group-layout'            => $this->layout !== GroupLayout::LIST ? $this->layout->value : null, // only include if not the default
        ];
        // FIXME: This is repetitive, e.g. CardList also does this (it just has a different default)
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        $innerContent = new Group($groupAttrs, []);
        $innerContent->innerComponents = array_map(function($link) use ($innerContent, $attributes) {
            if ($link instanceof Link) {
                $link->update_context($innerContent->get_bem_prefix());

                return $link;
            }

            return new Link([
                'context'       => $innerContent->get_bem_prefix(),
                'label'         => $link['label'] ?? $link['title'] ?? '',
                'description'   => $link['description'] ?? null,
                'url'           => $link['url'] ?? $link['href'] ?? '#',
                'target'        => $link['target'] ?? null,
            ]);
        }, $links);

        $updatedInnerComponents = $this->heading ? [new Heading([], $this->heading), $innerContent] : [$innerContent];
        parent::__construct($attributes, $updatedInnerComponents, 'components.LinkGroup.link-group');
    }
}
