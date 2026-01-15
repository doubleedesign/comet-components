<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class CardList extends WrappedLayoutComponent {
    use ColorTheme;
    use GroupLayoutType;

    /**
     * @var ?string $heading
     * @description Optional heading for the card list.
     */
    protected ?string $heading;

    /**
     * @var array<Card> $innerComponents
     * @description Cards to display in the list.
     */
    protected array $innerComponents = [];

    public function __construct(array $attributes, array $innerComponents) {
        $headingComponent = isset($attributes['heading']) ? new Heading([], $attributes['heading']) : null;
        $this->set_group_layout_from_attrs($attributes, GroupLayout::GRID);

        // Add a wrapper to each Card so that it can use container queries based on that
        $updatedInnerComponents = array_map(function($component) {
            return new Group(['context' => 'card-list__list', 'shortName'  => 'item'], [$component]);
        }, $innerComponents);

        $groupAttrs = [
            'colorTheme'                   => $this->colorTheme->value ?? 'primary',
            'shortName'                    => 'card-list',
            'role'                         => 'group',
            'data-group-layout'            => $this->layout->value
        ];

        // TODO: This is repetitive, e.g. LinkGroup also does this (it just has a different default)
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        // And a wrapper around the whole group to separate it from the heading
        $wrappedCards = (new Group($groupAttrs, $updatedInnerComponents))->set_bem_element('list');

        parent::__construct(
            $attributes,
            $headingComponent ? [$headingComponent, $wrappedCards] : [$wrappedCards],
            'components.CardList.card-list'
        );

        if ($this->get_is_nested()) {
            $this->tagName = Tag::DIV;
        }
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Always include card-list after any custom context/shortName classes,
        // so we don't end up with .card-list__list with no parent .card-list,
        // and so that styling applies correctly to all card lists regardless of their contextual name
        // FIXME: It's not actually applying to the outer wrapper, but adding get_filtered_classes() to render_with_wrapper causes duplication
        return array_unique(array_merge($classes, ['card-list']));
    }
}
