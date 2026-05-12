<?php
use Doubleedesign\Comet\Core\{Columns, Column};
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('Columns', function() {
    it('does not add an extra wrapper if no shortName is set', function() {
        ob_start();
        $component = new Columns([], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.columns',
            'div.columns__column'
        ]);
    });

    it('adds a wrapper if a shortName is provided', function() {
        ob_start();
        $component = new Columns(['shortName' => 'custom'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.custom',
            'div.custom__columns',
            'div.custom__columns__column'
        ]);
    });

    it('has the expected HTML and BEM structure when no context or shortName is provided', function() {
        ob_start();
        $component = new Columns([], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.columns',
            'div.columns__column'
        ]);
    });

    it('has the expected HTML and BEM structure when a shortName', function() {
        ob_start();
        $component = new Columns(['shortName' => 'custom'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.custom',
            'div.custom__columns',
            'div.custom__columns__column'
        ]);
    });

    it('has the expected HTML and BEM structure when context is provided', function() {
        ob_start();
        $component = new Columns(['context' => 'post-content'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.post-content__columns',
            'div.post-content__columns__column'
        ]);
    });

    it('has the expected HTML and BEM structure when both context and shortName are provided', function() {
        ob_start();
        $component = new Columns(['context' => 'post-content', 'shortName' => 'custom'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.post-content__custom',
            'div.post-content__custom__columns',
            'div.post-content__custom__columns__column'
        ]);
    });

    it('has the expected HTML and BEM structure if a column has its own shortName', function() {
        ob_start();
        $component = new Columns([], [new Column(['shortName' => 'sidebar'], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.columns',
            'div.columns__column columns__column--sidebar'
        ]);
    });

    it('has the expected HTML and BEM structure if a column has its own context', function() {
        ob_start();
        $component = new Columns([], [new Column(['context' => 'post-content'], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $hierarchy = PestUtils::getHtmlHierarchy($body);

        expect($hierarchy)->toEqual([
            'section.columns',
            'div.post-content__columns__column'
        ]);
    });

    test("Inner columns' background colour is ignored when they are all the same as the parent Columns", function() {
        ob_start();
        $component = new Columns(
            ['backgroundColor' => 'light'],
            [
                new Column(['backgroundColor' => 'light'], []),
                new Column(['backgroundColor' => 'light'], [])
            ]
        );
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = PestUtils::getElementsByClassName($dom, 'columns')[0];
        $columns = PestUtils::getElementsByClassName($dom, 'columns__column');

        expect($wrapper->hasAttribute('data-background'))->toBeTrue()
            ->and($columns[0]->hasAttribute('data-background'))->toBeFalse()
            ->and($columns[1]->hasAttribute('data-background'))->toBeFalse();
    });

    test("Inner column background colour is not ignored when one is different", function() {
        ob_start();
        $component = new Columns(
            ['backgroundColor' => 'light'],
            [
                new Column(['backgroundColor' => 'light'], []),
                new Column(['backgroundColor' => 'primary'], [])
            ]
        );
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = PestUtils::getElementsByClassName($dom, 'columns')[0];
        $columns = PestUtils::getElementsByClassName($dom, 'columns__column');

        expect($wrapper->hasAttribute('data-background'))->toBeTrue()
            ->and($columns[0]->hasAttribute('data-background'))->toBeFalse()
            ->and($columns[1]->hasAttribute('data-background'))->toBeTrue();
    });

    it('adds the layout stacking attribute if it is explicitly set to false', function() {
        ob_start();
        $component = new Columns(['allowStacking' => false], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $columns = PestUtils::getElementsByClassName($dom, 'columns')[0];

        expect($columns->hasAttribute('data-allow-layout-stacking'))->toBeTrue()
            ->and($columns->getAttribute('data-allow-layout-stacking'))->toEqual('false');
    });

    it('adds the layout stacking attribute if it is explicitly set to false and the component has a shortName set', function() {
        ob_start();
        $component = new Columns(['allowStacking' => false, 'shortName' => 'copy-image'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = PestUtils::getElementsByClassName($dom, 'copy-image')[0];
        $columns = PestUtils::getElementsByClassName($dom, 'copy-image__columns')[0];

        expect($wrapper->hasAttribute('data-allow-layout-stacking'))->toBeFalse()
            ->and($columns->hasAttribute('data-allow-layout-stacking'))->toBeTrue()
            ->and($columns->getAttribute('data-allow-layout-stacking'))->toEqual('false');
    });

    it('does not add the layout stacking attribute if it is not explicitly set', function() {
        ob_start();
        $component = new Columns([], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $body = $dom->getElementsByTagName('body')->item(0);
        $columns = PestUtils::getElementsByClassName($dom, 'columns')[0];

        expect($columns->hasAttribute('data-allow-layout-stacking'))->toBeFalse();
    });

    it('does not add the layout stacking attribute if it is not explicitly set and the component has a shortName set', function() {
        ob_start();
        $component = new Columns(['shortName' => 'copy-image'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = PestUtils::getElementsByClassName($dom, 'copy-image')[0];
        $columns = PestUtils::getElementsByClassName($dom, 'copy-image__columns')[0];

        expect($wrapper->hasAttribute('data-allow-layout-stacking'))->toBeFalse()
            ->and($columns->hasAttribute('data-allow-layout-stacking'))->toBeFalse();
    });

    it('does not add the layout stacking attribute if it is explicitly set to true', function() {
        ob_start();
        $component = new Columns(['allowStacking' => true], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $columns = PestUtils::getElementsByClassName($dom, 'columns')[0];

        expect($columns->hasAttribute('data-allow-layout-stacking'))->toBeFalse();
    });

    it('does not add the layout stacking attribute if it is explicitly set to true and the component has an explicit shortName', function() {
        ob_start();
        $component = new Columns(['shortName' => 'copy-image', 'allowStacking' => true], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $wrapper = PestUtils::getElementsByClassName($dom, 'copy-image')[0];
        $columns = PestUtils::getElementsByClassName($dom, 'copy-image__columns')[0];

        expect($wrapper->hasAttribute('data-allow-layout-stacking'))->toBeFalse()
            ->and($columns->hasAttribute('data-allow-layout-stacking'))->toBeFalse();
    });
});
