<?php
namespace Doubleedesign\Comet\Tests;
use Doubleedesign\Comet\TestUtils\WordPress\WpBridgeTestCase;
use Doubleedesign\Comet\Components\Heading;
use RuntimeException;

class HeadingTest extends WpBridgeTestCase {
    private function wpIntegrationTestCases_match(): array {
        return [
            'Handle heading level' => ["Hello, world!", ["level" => 3]],
            'Handle style'         => ["Hello, world!", ["level" => 2, "className" => "is-style-accent"]],
            'Handle anchor'        => ["Hello, world!", ["level" => 2, "id" => "hello-world"]],
        ];
    }

    private function wpIntegrationTestCases_differ(): array {
        return [
            'Handle alignment'     => ["Hello, world!", ["level" => 2, "textAlign" => "center"]],
        ];
    }

    /**
     * Test that the output of the Comet Heading component matches the output of the corresponding WordPress core block
     * in ways that are expected to match.
     * @dataProvider wpIntegrationTestCases_match
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function test_comet_matches_wp_core($content, $attributes): void {
        try {
            $wp_output = parent::$transformer->transform_block('core/heading', $attributes, [$content]);
        } catch (RuntimeException $e) {
            fwrite(STDERR, $e->getMessage());
            $this->fail($e->getMessage());
        }

        ob_start();
        $comet = new Heading($attributes, $content);
        $comet->render();
        $comet_output = ob_get_clean();

        fwrite(STDOUT, "WP output:\n$wp_output\n");
        fwrite(STDOUT, "Comet output:\n$comet_output\n");

        $this->assertEquals($wp_output, $comet_output);
    }


    /**
     * Test that the output of the Comet component differs from the output of the corresponding WordPress core block
     * in exactly the expected ways.
     * @dataProvider wpIntegrationTestCases_differ
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function test_comet_differs_from_wp_core($content, $attributes): void {
        try {
            $wp_output = parent::$transformer->transform_block('core/heading', $attributes, [$content]);
        } catch (RuntimeException $e) {
            fwrite(STDERR, $e->getMessage());
            $this->fail($e->getMessage());
        }

        ob_start();
        $comet = new Heading($attributes, $content);
        $comet->render();
        $comet_output = ob_get_clean();

        $this->assertEquals('<h2 class="has-text-align-center">Hello, world!</h2>', $wp_output);
        $this->assertEquals('<h2 style="text-align: center;">Hello, world!</h2>', $comet_output);
    }
}
