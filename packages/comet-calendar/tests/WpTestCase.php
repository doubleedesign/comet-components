<?php
namespace Doubleedesign\Comet\WordPress\Calendar\Tests;
use Brain\Monkey;
use Mockery;
use PHPUnit\Framework\TestCase;
use Spies;

abstract class WpTestCase extends TestCase {
    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void {
        parent::tearDown();
        Monkey\tearDown();
        Mockery::close();
        Spies\finish_spying();
    }
}
