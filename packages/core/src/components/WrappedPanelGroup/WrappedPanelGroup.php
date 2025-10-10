<?php
namespace Doubleedesign\Comet\Core;

class WrappedPanelGroup extends WrappedLayoutComponent {
    /**
     * @var array<Renderable> $beforeComponents
     * @description Components to render before the main component (e.g. heading, intro text).
     */
    protected array $beforeComponents = [];

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
}
