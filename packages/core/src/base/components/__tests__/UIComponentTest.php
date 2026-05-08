<?php

use Doubleedesign\Comet\Core\{BladeService, PreprocessedHTML, UIComponent};
use Doubleedesign\Comet\TestUtils\PestUtils;

function create_mock_component(array $attributes, array $innerComponents, string $bladeFile): object {
    return new class($attributes, $innerComponents, $bladeFile) extends UIComponent {
        public function __construct(array $attributes, array $innerComponents, string $bladeFile) {
            parent::__construct($attributes, $innerComponents, $bladeFile);
        }

        public function render(): void {
            $blade = BladeService::getInstance();

            echo $blade->make('base.components.__tests__.test', [
                'classes'      => $this->get_filtered_classes(),
                'attributes'   => $this->get_html_attributes(),
                'children'     => $this->innerComponents
            ])->render();
        }
    };
}

function create_mock_component_tree(array $attributes): UIComponent {
    $level3 = create_mock_component([], [new PreprocessedHTML([], "Mock UI component")], 'components.Thing.ThingGroup.ThingGroupItem.thing-group-item');
    $level2 = create_mock_component([], [$level3], 'components.Thing.ThingGroup.thing-group');

    return create_mock_component($attributes, [$level2], 'components.Thing.thing');

}

describe('UIComponent', function() {
    it('has the expected default BEM structure', function() {
        $instance = create_mock_component_tree([]);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'div.thing',
            'div.thing__group',
            'div.thing__group__item'
        ]);
    });

    it('propagates the expected BEM structure to inner components when explicit context is provided', function() {
        $instance = create_mock_component_tree(['context' => 'article']);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'div.article__thing',
            'div.article__thing__group',
            'div.article__thing__group__item'
        ]);
    });

    it('propagates the expected BEM structure to inner components when shortName is provided', function() {
        $instance = create_mock_component_tree(['shortName' => 'post']);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'div.post',
            'div.post__thing-group',
            'div.post__thing-group__item'
        ]);
    });

    it('propagates the expected BEM structure to inner components when both context and shortName are provided', function() {
        $instance = create_mock_component_tree(['context' => 'projects', 'shortName' => 'archive']);

        ob_start();
        $instance->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

        expect($hierarchy)->toEqual([
            'div.projects__archive',
            'div.projects__archive__thing-group',
            'div.projects__archive__thing-group__item'
        ]);
    });
});
