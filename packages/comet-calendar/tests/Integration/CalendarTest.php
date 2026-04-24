<?php

use Doubleedesign\Comet\WordPress\Calendar\Calendar;
use function Spies\{stub_function};

it('uses the correct test case class', function() {
    if (!method_exists($this, 'is_wp_test_case')) {
        exit(1);
    }

    expect($this->is_wp_test_case())->toEqual('integration');
});

describe('Calendar page / event archive', function() {
    // Note: There are some WP functions mocked globally in Pest.php
    // mostly those called within the classes we're testing integrations for, but we don't need to vary for what we're testing.
    beforeEach(function() {
        stub_function('get_option');
        stub_function('is_admin')->will_return(false);
        stub_function('current_time')->when_called->with('Y-m-d')->will_return('2026-08-25');
        stub_function('get_taxonomies')->will_return([]);
        stub_function('is_user_logged_in')->will_return(false);
    });

    it('returns no posts if "show past events" is set to "never"', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('never');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query)->posts;

        expect($result)->toBeEmpty();
    });

    it('excludes future events', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('always');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query);

        \Symfony\Component\VarDumper\VarDumper::dump($result);

        // TODO: Assert actual results
    });

    it('sorts by the "sort_date" meta key', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('always');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query)->posts;

        // TODO: Assert actual results
    });

    it('sorts by most recent event first', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('always');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query)->posts;

        // TODO: Assert actual results
    });

    it('filters correctly if "show past events" is set to "current year"', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('current_year');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query)->posts;

        // TODO: Assert actual results
    });

    it('filters correctly if "show past events" is set to "past year"', function() {
        stub_function('get_option')->when_called->with('options_show_past_events')->will_return('current_year');
        $query = new WP_Query(['post_type' => 'event']);

        $instance = new Calendar();
        $result = $instance->customise_default_event_archive($query)->posts;

        // TODO: Assert actual results
    });
});
