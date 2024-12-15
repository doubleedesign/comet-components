<?php
namespace Doubleedesign\Comet\Tests;
use Doubleedesign\Comet\TestUtils\WordPress\WpBridgeTestCase;
use Doubleedesign\Comet\Components\Heading;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\ExpectationFailedException;
use RuntimeException, InvalidArgumentException;

class HeadingTest extends WpBridgeTestCase {

    /**
     * @throws AssertionFailedError
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException // Can be thrown by the Comet component, but we assume it won't be here
     */
    public function test_comet_matches_wp_core(): void {
        $content = 'Hello, world!';
        $attributes = ['className' => 'is-style-lead', 'level' => 2];

        try {
            $wp_output = parent::$transformer->transform_block('core/heading', $attributes, [$content]);
        }
        catch (RuntimeException $e) {
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
}
