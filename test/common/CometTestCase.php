<?php
namespace Doubleedesign\Comet\TestUtils;
// BrainMonkey provides Patchwork and Mockery in a convenient package compatible with PHPUnit and my custom browser test output
// as well as some convenience patching of WordPress stuff like actions, filters, and utility functions.
// The only downsides are some version compatibility issues + it lacks Patchwork's ability to redefine internals like require().
// In the future it *may* make sense to move this to WpBridgeTestCase and have CometTestCase use Patchwork directly, to reduce dependencies and version compatibility issues,
// and as a proxy guardrail for ensuring Comet components themselves do not have WordPress dependencies (who learnt the hard way that esc_html is not a built-in PHP function? I did).
use Brain\Monkey;
use PHPUnit\Framework\TestCase;

class CometTestCase extends TestCase {

    public static function setUpBeforeClass(): void {
        parent::setUpBeforeClass();
    }

    protected function setUp(): void {
        Monkey\setUp();
        parent::setUp();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }
}
