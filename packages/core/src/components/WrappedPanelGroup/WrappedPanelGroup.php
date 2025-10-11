<?php
namespace Doubleedesign\Comet\Core;

#[AllowedTags([Tag::DIV, Tag::SECTION])]
#[DefaultTag(Tag::SECTION)]
class WrappedPanelGroup extends WrappedLayoutComponent {
    /**
     * @var array<Renderable> $beforeComponents
     * @description Components to render before the main component (e.g. heading, intro text).
     */
    protected array $beforeComponents = [];

    /**
     * @var ?array<string> $classes
     * @description Not used - this component renders its children directly
     */
    protected ?array $classes = [];

    public function __construct(array $attributes, array $beforeComponents, PanelGroupComponent $innerSelf) {
        $this->beforeComponents = $beforeComponents;

        $wrappedIntro = new Group(
            [
                'context'    => $attributes['shortName'],
                'shortName'  => 'intro',
                'colorTheme' => $attributes['colorTheme'] ?? null
            ],
            $beforeComponents
        );

        parent::__construct($attributes, [$wrappedIntro, $innerSelf], 'components.WrappedPanelGroup.wrapped-panel-group');
    }

    public function get_filtered_classes(): array {
        // This component renders its children directly, so this is here for clarity/documentation
        return [];
    }
}
