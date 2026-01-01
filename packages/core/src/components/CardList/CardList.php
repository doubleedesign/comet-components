<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class CardList extends WrappedLayoutComponent {
    use ColorTheme;

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

    /**
     * @var bool $gridLayout
     * @description Whether to use a grid layout for the cards.
     */
    protected bool $gridLayout = true;

    /**
     * @var ?int $maxPerRow
     * @description The maximum number of cards to display per row in grid layout. If not set, will be derived from the number of cards and their divisibility by 4, 3, or 2.
     */
    protected ?int $maxPerRow = null;

    public function __construct(array $attributes, array $innerComponents) {
        $headingComponent = $attributes['heading'] ? new Heading([], $attributes['heading']) : null;
        $this->gridLayout = $attributes['gridLayout'] ?? $this->gridLayout; // TODO: Do we need this? Don't seem to be using it
        $this->maxPerRow = $attributes['maxPerRow'] ?? $this->maxPerRow;

        // Add a wrapper to each Card so that it can use container queries based on that
        $updatedInnerComponents = array_map(function($component) {
            return new Group(['context' => 'card-list__list', 'shortName'  => 'item'], [$component]);
        }, $innerComponents);

        // And a wrapper around the whole group to separate it from the heading
        $wrappedCards = new Group(
            [
                'shortName'        => 'card-list',
                'role'             => 'group',
                'data-max-per-row' => $this->get_col_count(),
                'colorTheme'       => $attributes['colorTheme'] ?? null,
            ],
            $updatedInnerComponents
        )->set_bem_element('list');

        parent::__construct(
            $attributes,
            $headingComponent ? [$headingComponent, $wrappedCards] : [$wrappedCards],
            'components.CardList.card-list'
        );
    }

    private function get_col_count(): int {
        if (isset($this->maxPerRow) && $this->maxPerRow > 0) {
            return $this->maxPerRow;
        }

        $qty = count($this->innerComponents);

        if ($qty % 4 === 0) {
            return 4;
        }

        if ($qty % 3 === 0) {
            return 3;
        }

        if ($qty % 2 === 0) {
            return 2;
        }

        return 1;
    }
}
