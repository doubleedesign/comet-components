<?php
/** @noinspection PhpUnhandledExceptionInspection */
namespace Doubleedesign\Comet\Core;
use PHPUnit\Framework\{TestCase, Attributes\TestDox, Attributes\Test};
use DOMDocument;
use function Patchwork\redefine;

#[TestDox("Container")]
class ContainerTest extends TestCase {

	#[TestDox('It ignores the backgroundColor attribute when it matches the global background')]
	#[Test] public function same_bg_as_global_is_ignored() {
		redefine('Doubleedesign\Comet\Core\CometConfig::get_global_background', fn() => 'dark');

		ob_start();
		$component = new Container(['backgroundColor' => 'dark'], []);
		$component->render();
		$output = ob_get_clean();

		$dom = new DOMDocument();
		@$dom->loadHTML($output);
		$container = $dom->getElementsByTagName('section')->item(0);

		$this->assertFalse($container->hasAttribute('data-background'));
	}
}
