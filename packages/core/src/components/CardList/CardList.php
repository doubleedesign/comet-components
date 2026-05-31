<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class CardList extends LayoutComponent {
    use ColorPair;
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
        $this->set_color_pair($attributes);
        $headingComponent = isset($attributes['heading']) ? new Heading([], $attributes['heading']) : null;
        $linkComponent = isset($attributes['link']) ? new Button($attributes['link'], $attributes['link']['title'] ?? 'View more') : null;

        // Add a wrapper to each Card so that it can use container queries based on that
        $updatedInnerComponents = array_map(function($component) {
            return new Group(['shortName' => 'item'], [$component]);
        }, $innerComponents);

        // And a wrapper around the whole card group to separate it from the header and footer
        $groupAttrs = $this->get_inner_group_attributes();
        if ($this->layout === GroupLayout::GRID) {
            $groupAttrs['data-max-per-row'] = $this->maxPerRow;
        }
        $wrappedCards = (new Group($groupAttrs, $updatedInnerComponents))->set_bem_element('list');

        $header = $headingComponent ? new Group(['shortName' => 'header'], [$headingComponent]) : null;
        if ($linkComponent !== null & $this->hAlign === Alignment::START) {
            $header = new Group(
                ['shortName' => 'header'],
                array_filter([$headingComponent, $linkComponent], fn($component) => $component !== null)
            );
        }

        $footer = null;
        if ($linkComponent !== null && $this->hAlign !== Alignment::START) {
            $footer = new Group(['shortName' => 'footer'], [$linkComponent]);
        }

        parent::__construct($attributes, array_filter([$header, $wrappedCards, $footer]), 'components.CardList.card-list');

        if ($this->get_is_nested()) {
            $this->tagName = Tag::DIV;
        }
    }

    protected function get_inner_group_attributes(): array {
        return array(
            'shortName'                    => 'card-list',
            'role'                         => 'group',
            'data-group-layout'            => $this->layout->value
        );
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
