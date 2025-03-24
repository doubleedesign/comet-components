<?php
namespace Doubleedesign\Comet\Core\__tests__;
use Doubleedesign\Comet\Core\Paragraph;
use Doubleedesign\Comet\TestUtils\WordPress\WpBridgeTestCase;
use RuntimeException;

class ParagraphTest extends WpBridgeTestCase {
	private function wpIntegrationTestCases_match(): array {
		return [
			'Default'     => ["Hello, world!", []],
			'Style class' => ["Hello, world!", ["className" => "is-style-lead"]],
		];
	}

	/**
	 * Test that the output of the Comet Heading component matches the default output
	 * of the corresponding WordPress core block in ways that are expected to match.
	 * @dataProvider wpIntegrationTestCases_match
	 * @noinspection PhpUnhandledExceptionInspection
	 */
	public function test_comet_matches_wp_core($content, $attributes) {
		try {
			$wp_output = parent::$transformer->transform_block('core/paragraph', $attributes, $content);
		}
		catch(RuntimeException $e) {
			fwrite(STDERR, $e->getMessage());
			$this->fail($e->getMessage());
		}

		ob_start();
		$comet = new Paragraph($attributes, $content);
		$comet->render();
		$comet_output = ob_get_clean();

		fwrite(STDOUT, "WP output:\n$wp_output\n");
		fwrite(STDOUT, "Comet output:\n$comet_output\n");

		$this->assertEquals($wp_output, $comet_output);
	}
}
