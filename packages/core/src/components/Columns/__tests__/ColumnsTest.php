<?php

use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use \Doubleedesign\Comet\Core\Column;
use \Doubleedesign\Comet\Core\Columns;
use function Phluent\Expect;


test('inner columns same bg is ignored', function () {
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
});
