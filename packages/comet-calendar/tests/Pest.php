<?php

use Doubleedesign\Comet\WordPress\Calendar\Tests\{WpIntegrationTestCase, WpUnitTestCase};
use Spies\Spy;
use function Brain\Monkey\Functions\when;
use function Patchwork\relay;

/**
 * Note: This configuration works in combination with phpunit.xml in the monorepo root,
 * which defines this tests directory as a test suite and loads this file as the bootstrap.
 * This setup allows us to test this plugin (and other packages in the monorepo) without duplicating dependencies.
 */

require_once __DIR__ . '/../vendor/autoload.php';
define('WP_DEBUG', false);
define('ABSPATH', __DIR__ . '/../../../../wordpress-canvas/app/');
define('WPINC', 'wp-includes');

// Include the WP_Query class so it can be mocked in unit tests and actually used in integration tests
require_once __DIR__ . '/../../../../wordpress-canvas/app/wp-includes/class-wp-query.php';

// Scope by __DIR__ so it correctly finds the files within this package
$unit = __DIR__ . '/Unit';
$integration = __DIR__ . '/Integration';
pest()->extend(WpUnitTestCase::class)->in($unit);
pest()->extend(WpIntegrationTestCase::class)->in($integration);

uses()->beforeEach(function() {
    when('plugin_dir_path')->justReturn('/');

    $this->actions = [];
    $this->filters = [];

    /**
     * These patches intercept the given WordPress functions before BrainMonkey does if real instances
     * of plugin classes are instantiated, allowing us to pass in method spies as well as run the real methods,
     * which allows us to both assert that the methods were called and that the result would be correct.
     */
    when('add_action')->alias(function($hook, $callback) {
        // Store the added action in the test instance
        $this->actions[$hook][] = $callback;
        // Call the BrainMonkey mock too
        relay(func_get_args());
    });

    when('do_action')->alias(function($hook, ...$args) {
        // Run the functions registered for this hook
        if (isset($this->actions[$hook])) {
            foreach ($this->actions[$hook] as $callback) {
                call_user_func_array($callback, $args);
            }
        }

        // Call the BrainMonkey mock too
        relay(func_get_args());
    });

    when('add_filter')->alias(function($hook, $callback) {
        // Store the added filter in the test instance
        $this->filters[$hook][] = $callback;
        // Call the BrainMonkey mock too
        relay(func_get_args());
    });

    // TODO: Fix this not working with multiple arguments being passed to the callback
    when('apply_filters')->alias(function($hook, $value, ...$extra) {
        // Run the functions registered for this hook
        if (isset($this->filters[$hook])) {
            foreach ($this->filters[$hook] as $callback) {
                // If a spy is provided as a third argument, call that as well as the registered callback
                // This fixes the issue where the result is correct because the correct method gets called,
                // but $this inside the class being tested is the original mock not the spy object created from that mock
                if (isset($extra[0]) && $extra[0] instanceof Spy) {
                    $extra[0]->call($value);
                }

                $value = call_user_func($callback, $value);
            }
        }

        // Call the BrainMonkey mock too
        relay(func_get_args());

        // Return the final value after all available filters have been applied, or the default value if there were none
        return $value;
    });

    when('__')->returnArg(1);

})->in($unit, $integration);

uses()->beforeEach(function() {
    when('get_current_user_id')->justReturn(1);
    when('get_post_stati')->justReturn(['publish']);
    when('wp_is_serving_rest_request')->justReturn(false);
    when('remove_all_filters')->justReturn();
    when('wp_using_ext_object_cache')->justReturn(false);
    when('wp_cache_get_last_changed')->justReturn(time());
    when('wp_cache_get_salted')->justReturn(false);
    when('wp_cache_set_salted')->justReturn(true);
    when('wp_debug_backtrace_summary')->alias(fn() => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
    when('sanitize_key')->returnArg(1);
    when('addslashes_gpc')->returnArg(1);

    // This is a straight-up copy of the real function,
    // because we can't include the whole functions.php file because of conflicts with BrainMonkey
    when('wp_list_pluck')->alias(function($list, $field, $key = null) {
        if (!is_array($list)) {
            return array();
        }

        $util = new WP_List_Util($list);

        return $util->pluck($field, $key);
    });

    // This is also a copy of the real function
    when('wp_parse_args')->alias(function($args, $defaults = '') {
        if (is_object($args)) {
            $parsed_args = get_object_vars($args);
        }
        elseif (is_array($args)) {
            $parsed_args = $args;
        }
        else {
            wp_parse_str($args, $parsed_args);
        }

        if (is_array($defaults)) {
            return array_merge($defaults, $parsed_args);
        }

        return $parsed_args;
    });

})->in($integration);
