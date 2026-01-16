<?php

namespace Doubleedesign\Comet\WordPress\Calendar\Tests;

abstract class WpUnitTestCase extends WpTestCase {
    public function is_wp_test_case(): string {
        return 'unit';
    }
}
