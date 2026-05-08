<?php
use Doubleedesign\Comet\Core\{Columns, Column};
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('Columns', function() {
    it('adds "wrapper" to the outer section class name if no shortName is set', function() {
        ob_start();
        $component = new Columns([], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $section = $dom->getElementsByTagName('section')->item(0);
        $innerDiv = $dom->getElementsByTagName('div')->item(1);

        expect($section->getAttribute('class'))->toEqual('columns-wrapper')
            ->and($innerDiv->getAttribute('class'))->toEqual('columns');
    });

    it('does not modify the outer section class name if a shortName is set', function() {
        ob_start();
        $component = new Columns(['shortName' => 'custom'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $section = $dom->getElementsByTagName('section')->item(0);

        expect($section->getAttribute('class'))->toEqual('custom');
    });

	it('has the expected default HTML and BEM structure', function() {
		ob_start();
		$component = new Columns([], [new Column([], [])]);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$body = $dom->getElementsByTagName('body')->item(0);
		$hierarchy = PestUtils::getHtmlHierarchy($body);

		expect($hierarchy)->toEqual([
			'section.columns-wrapper',
			'div.columns-wrapper__container',
			'div.columns',
			'div.columns__column'
		]);
	});

    it('renders as a div without a container, not a section, when nested', function() {
        ob_start();
        $component = new Columns(['isNested' => true], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $section = $dom->getElementsByTagName('section')->item(0);
        $div = $dom->getElementsByTagName('div')->item(0);

        expect($section)->toBeNull()
            ->and($div)->not()->toBeNull()
            ->and($div->getAttribute('class'))->toEqual('columns');
    });

	it('has the expected HTML and BEM structure when nested', function() {
		ob_start();
		$component = new Columns(['isNested' => true], [new Column([], [])]);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$body = $dom->getElementsByTagName('body')->item(0);
		$hierarchy = PestUtils::getHtmlHierarchy($body);

		expect($hierarchy)->toEqual([
			'div.columns',
			'div.columns__column'
		]);
	});

    it('does not modify the class name if the component is nested', function() {
        ob_start();
        $component = new Columns(['isNested' => true], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $div = $dom->getElementsByTagName('div')->item(0);

        expect($div->getAttribute('class'))->toEqual('columns');
    });

    it('prefixes the inner div class name in BEM style if a shortName is provided', function() {
        ob_start();
        $component = new Columns(['shortName' => 'custom'], [new Column([], [])]);
        $component->render();
        $output = ob_get_clean();

        $dom = new DOMDocument();
        @$dom->loadHTML($output);
        $innerDiv = $dom->getElementsByTagName('div')->item(1);

        expect($innerDiv->getAttribute('class'))->toEqual('custom__columns');
    });

	it('has the expected HTML and BEM structure when a shortName is provided', function() {
		ob_start();
		$component = new Columns(['shortName' => 'copy-image'], [new Column([], [])]);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$body = $dom->getElementsByTagName('body')->item(0);
		$hierarchy = PestUtils::getHtmlHierarchy($body);

		expect($hierarchy)->toEqual([
			'section.copy-image',
			'div.copy-image__container',
			'div.copy-image__columns',
			'div.copy-image__column'
		]);
	});

	// FIXME this isn't passing but leaving it for now because I'm planning related changes
	// and having doubled-up backgrounds won't break anything here
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
        $wrapper = $dom->getElementsByTagName('section')->item(0);
        $columns = PestUtils::getElementsByClassName($wrapper, 'columns__column');

        expect($wrapper->hasAttribute('data-background'))->toBeTrue()
            ->and($columns[0]->hasAttribute('data-background'))->toBeFalse()
            ->and($columns[1]->hasAttribute('data-background'))->toBeFalse();
    });

    test("Inner columns' background colour is not ignored when one is different", function() {
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
        $wrapper = $dom->getElementsByTagName('section')->item(0);
        $columns = PestUtils::getElementsByClassName($wrapper, 'columns__column');

        expect($wrapper->hasAttribute('data-background'))->toBeTrue()
            ->and($columns[0]->hasAttribute('data-background'))->toBeTrue()
            ->and($columns[1]->hasAttribute('data-background'))->toBeTrue();
    });
});
