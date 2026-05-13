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

function create_mock_component_tree_with_blade_hierarchy(array $attributes): UIComponent {
    $level3 = create_mock_component([], [new PreprocessedHTML([], "Mock UI component")], 'components.Thing.ThingGroup.ThingGroupItem.thing-group-item');
    $level2 = create_mock_component([], [$level3], 'components.Thing.ThingGroup.thing-group');

    return create_mock_component($attributes, [$level2], 'components.Thing.thing');
}

function create_mock_component_tree_with_mixed_components(array $attributes): UIComponent {
    $level3 = create_mock_component([], [new PreprocessedHTML([], "Mock UI component")], 'components.Copy.copy');
    $level2 = create_mock_component([], [$level3], 'components.Group.group');

    return create_mock_component($attributes, [$level2], 'components.Thing.thing');
}

function create_mock_component_tree_very_mixed(array $attributes): UIComponent {
    $level4 = create_mock_component([], [new PreprocessedHTML([], "Mock UI component")], 'components.Copy.copy');
    $level3 = create_mock_component([], [$level4], 'components.List.ListItem.list-item');
    $level2 = create_mock_component([], [$level3], 'components.List.list');

    return create_mock_component($attributes, [$level2], 'components.Thing.thing');
}

describe('UIComponent', function() {
    describe('Components in a hierarchy that matches their directory structure and blade file paths', function() {
        it('has the expected default BEM structure', function() {
            $instance = create_mock_component_tree_with_blade_hierarchy([]);

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
            $instance = create_mock_component_tree_with_blade_hierarchy(['context' => 'article']);

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
            $instance = create_mock_component_tree_with_blade_hierarchy(['shortName' => 'post']);

            ob_start();
            $instance->render();
            $output = ob_get_clean();

            $dom = new DOMDocument();
            @$dom->loadHTML($output);
            $wrapper = $dom->getElementsByTagName('body')->item(0);
            $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

            // thing-group should NOT become thing__group here because there is no "thing" block, "post" is the block.
            expect($hierarchy)->toEqual([
                'div.post',
                'div.post__thing-group',
                'div.post__thing-group__item'
            ]);
        });

        it('propagates the expected BEM structure to inner components when both context and shortName are provided', function() {
            $instance = create_mock_component_tree_with_blade_hierarchy(['context' => 'projects', 'shortName' => 'archive']);

            ob_start();
            $instance->render();
            $output = ob_get_clean();

            $dom = new DOMDocument();
            @$dom->loadHTML($output);
            $wrapper = $dom->getElementsByTagName('body')->item(0);
            $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

            // thing-group should NOT become thing__group here because there is no "thing" block.
            expect($hierarchy)->toEqual([
                'div.projects__archive',
                'div.projects__archive__thing-group',
                'div.projects__archive__thing-group__item'
            ]);
        });
    });

    describe('Mixed components in a hierarchy created by their use as inner components', function() {
        it('propagates the expected BEM structure to inner components', function() {
            $instance = create_mock_component_tree_with_mixed_components([]);

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
                'div.thing__group__copy'
            ]);
        });
    });

    describe('Mixed components including some where BEM segments should have parts de-duplicated', function() {
        it('propagates the expected BEM structure to inner components', function() {
            $instance = create_mock_component_tree_very_mixed([]);

            ob_start();
            $instance->render();
            $output = ob_get_clean();

            $dom = new DOMDocument();
            @$dom->loadHTML($output);
            $wrapper = $dom->getElementsByTagName('body')->item(0);
            $hierarchy = PestUtils::getHtmlHierarchy($wrapper);

            // list -> list-item should be deduplicated to list__item
            expect($hierarchy)->toEqual([
                'div.thing',
                'div.thing__list',
                'div.thing__list__item',
                'div.thing__list__item__copy'
            ]);
        });
    });
});
