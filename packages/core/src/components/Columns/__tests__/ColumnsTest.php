<?php
use Doubleedesign\Comet\Core\{Columns, Column};
use Doubleedesign\Comet\TestUtils\PestUtils;

describe('Columns', function() {

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
		$columnSet = $dom->getElementsByTagName('div')->item(0);
		$columns = PestUtils::getElementsByClassName($columnSet, 'column');

		expect($columnSet->hasAttribute('data-background'))->toBeTrue()
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
		$columnSet = $dom->getElementsByTagName('div')->item(0);
		$columns = PestUtils::getElementsByClassName($columnSet, 'column');

		expect($columnSet->hasAttribute('data-background'))->toBeTrue()
			->and($columns[0]->hasAttribute('data-background'))->toBeTrue()
			->and($columns[1]->hasAttribute('data-background'))->toBeTrue();
	});
});
