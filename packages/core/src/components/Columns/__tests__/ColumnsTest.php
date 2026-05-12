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
});
