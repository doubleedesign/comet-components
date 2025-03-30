<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use DOMDocument;
use function Phluent\Expect;

#[TestDox("Columns")]
class ColumnsTest extends TestCase {

	#[TestDox('If the Columns component and all its inner columns have the same background color, the inner columns\' background color is ignored')]
	#[Test] public function inner_columns_same_bg_is_ignored() {
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
		$column1 = $columnSet->getElementsByTagName('div')->item(0);
		$column2 = $columnSet->getElementsByTagName('div')->item(1);

		Expect($columnSet->hasAttribute('data-background'))->toBeTrue();
		Expect($column1->hasAttribute('data-background'))->toBeFalse();
		Expect($column2->hasAttribute('data-background'))->toBeFalse();
	}
}
