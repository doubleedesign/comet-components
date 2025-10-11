<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV])]
#[DefaultTag(Tag::DIV)]
class CardList extends LayoutComponent {
    use ColorTheme;

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
        // Add a wrapper to each Card so that it can use container queries based on that
        $updatedInnerComponents = array_map(function($component) {
            return new Group(
                [
                    'context'    => 'card-list',
                    'shortName'  => 'item',
                ],
                [$component]
            );
        }, $innerComponents);

        parent::__construct($attributes, $updatedInnerComponents, 'components.CardList.card-list');
        $this->set_color_theme_from_attrs($attributes);
        $this->gridLayout = $attributes['gridLayout'] ?? $this->gridLayout;
        $this->maxPerRow = $attributes['maxPerRow'] ?? $this->maxPerRow;
    }

    protected function get_html_attributes(): array {
        $attributes = array_merge(
            parent::get_html_attributes(),
            array(
                'role'             => 'group'
            )
        );

        if ($this->gridLayout) {
            $attributes['data-max-per-row'] = $this->get_col_count();
        }

        if ($this->colorTheme) {
            $attributes['data-color-theme'] = $this->colorTheme->value;
        }

        return $attributes;
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
