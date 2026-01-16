<?php
namespace Doubleedesign\Comet\WordPress\Calendar\Tests;

abstract class WpIntegrationTestCase extends WpTestCase {
    protected function setUp(): void {
        parent::setUp();
        $this->init_database_connection();

        // Include prerequisite WP global functions and classes for our particular integration tests to work
        // (in addition to those loaded in the parent class and Pest.php)
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/post.php';
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/taxonomy.php';
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/meta.php';
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/class-wp-list-util.php';
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/class-wp-tax-query.php';
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/class-wp-meta-query.php';
    }

    public function is_wp_test_case(): string {
        return 'integration';
    }

    protected function init_database_connection(): void {
        require_once __DIR__ . '/../../../../vanilla-playground/app/wp-includes/class-wpdb.php';
        global $wpdb;
        $dbUser = 'root';
        $dbPassword = '';
        $dbName = 'vanilla_dev';
        $dbHost = 'localhost:3309';
        $wpdb = new \wpdb($dbUser, $dbPassword, $dbName, $dbHost);
    }
}
