<?php
namespace Doubleedesign\Comet\Core;

/**
 * EventList component
 *
 * @package Doubleedesign\Comet\Core
 * @version 1.0.0
 * @description
 */
#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class EventList extends LayoutComponent {
    use ColorTheme;
    use GroupLayoutType;
    use NestedState;

    /**
     * @var ?string $heading
     * @description The heading for the event list.
     */
    protected ?string $heading;

    /**
     * @var array<EventCard> $innerComponents
     * @description Event cards to display.
     */
    protected array $innerComponents;

    /**
     * Link to a page to view all events; include only if this is not already the "all events" context.
     *
     * @var string|null
     */
    protected ?string $viewAllUrl;

    public function __construct(array $attributes, array $innerComponents) {
        $attributes['shortName'] = $attributes['shortName'] ?? 'events';
        $this->bladeFile = $bladeFile ?? 'components.EventList.event-list';
        $this->set_group_layout($attributes);
        $this->set_is_nested($attributes['isNested'] ?? false);
        $this->set_color_theme($attributes);
        $this->viewAllUrl = $attributes['viewAllUrl'] ?? null;

        $groupAttrs = [
            'tagName'                      => 'ul',
            'context'                      => 'events',
            'shortName'                    => 'list',
            'colorTheme'                   => $this->colorTheme->value ?? 'primary',
            'data-group-layout'            => $this->layout->value
        ];

        // TODO: This is repetitive, CardList and LinkGroup also do this (one just has a different default)
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }

        $wrappedCards = (new Group($groupAttrs, $innerComponents));

        $headingComponent = $attributes['heading'] ? new Heading(['context' => 'events'], $attributes['heading']) : null;
        $linkComponent = $this->viewAllUrl ? new Button(['classes' => ['button--view-all'], 'href' => $this->viewAllUrl], 'View all') : null; // TODO: Make the label configurable

        // Call constructor so BEM context gets initialised
        parent::__construct(
            $attributes,
            array_filter([$headingComponent, $wrappedCards, $linkComponent]),
            'components.EventList.event-list'
        );

        // ...then apply it to the cards
        $wrappedCards->innerComponents = array_map(function($card) use ($wrappedCards) {
            if ($card instanceof EventCard && !$card->get_context()) {
                $card->update_context($wrappedCards->get_bem_prefix());
            }

            return $card;
        }, $wrappedCards->innerComponents);
    }
}
