<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class CardList extends LayoutComponent {
    use ColorTheme;
    use GroupLayoutType;
    use NestedState;

    /**
     * @var ?string $heading
     * @description Optional heading for the card list.
     */
    protected ?string $heading;

    /**
     * @var array|null $link
     * @description Optional link to display as a call-to-action, such as "View all". Placement of the link is determined by the horizontal alignment setting.
     */
    protected ?array $link;

    /**
     * @var array<Card> $innerComponents
     * @description Cards to display in the list.
     */
    protected array $innerComponents = [];

    public function __construct(array $attributes, array $innerComponents) {
        $this->set_group_layout($attributes);
        $this->set_layout_alignment($attributes, Alignment::START);
        $this->set_is_nested($attributes['isNested'] ?? false);
        $headingComponent = isset($attributes['heading']) ? new Heading([], $attributes['heading']) : null;
        $linkComponent = isset($attributes['link']) ? new Button($attributes['link'], $attributes['link']['title'] ?? 'View more') : null;

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

        // TODO: This is repetitive, e.g. LinkGroup also does this (it just has a different default, Gallery also uses grid layout...)
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        // And a wrapper around the whole card group to separate it from the header and footer
        $wrappedCards = (new Group($groupAttrs, $updatedInnerComponents))->set_bem_element('list');

        $header = new Group(
            ['context' => 'card-list', 'shortName' => 'header'],
            [$headingComponent]
        );
        if ($linkComponent !== null & $this->hAlign === Alignment::START) {
            $header = new Group(
                ['context' => 'card-list', 'shortName' => 'header'],
                [$headingComponent, $linkComponent]
            );
        }

        $footer = null;
        if ($linkComponent !== null && $this->hAlign !== Alignment::START) {
            $footer = new Group(
                ['context' => 'card-list', 'shortName' => 'footer'],
                [$linkComponent]
            );
        }

        parent::__construct($attributes, array_filter([$header, $wrappedCards, $footer]), 'components.CardList.card-list');

        if ($this->get_is_nested()) {
            $this->tagName = Tag::DIV;
        }
    }

    public function get_filtered_classes(): array {
        $classes = parent::get_filtered_classes();

        // Always include card-list after any custom context/shortName classes,
        // so we don't end up with .card-list__list with no parent .card-list,
        // and so that styling applies correctly to all card lists regardless of their contextual name
        return array_unique(array_merge($classes, ['card-list']));
    }
}
