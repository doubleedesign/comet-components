<?php

use Doubleedesign\Comet\WordPress\Calendar\Calendar;
use function Spies\{any, expect_spy, mock_object, stub_function};

it('uses the correct test case class', function() {
    expect($this->is_wp_test_case())->toEqual('unit');
});

// Even with $spy->clear_call_record() in beforeEach() above, the query was maintaining state between tests if shared.
function createFreshQueryAndSpy(): array {
    $query = mock_object(new WP_Query())->and_ignore_missing();
    $query->add_method('get')->when_called->with('post_type')->will_return('event');
    $querySet = $query->spy_on_method('set');

    return [$query, $querySet];
}

describe('Calendar page / event archive', function() {
    beforeEach(function() {
        stub_function('get_option');
        stub_function('is_admin')->will_return(false);
        stub_function('current_time')->when_called->with('Y-m-d')->will_return('2026-08-25');
    });

    it('sorts by the sort_date meta value in the admin', function() {
        stub_function('is_admin')->will_return(true);
        list($query, $querySet) = createFreshQueryAndSpy();
        new Calendar();

        do_action('pre_get_posts', $query);

        expect_spy($querySet)->to_have_been_called->with('meta_key', 'sort_date');
        expect($querySet)->was_called()->toBeTrue();
    });

    it('sorts by the sort_date meta value on the front-end', function() {
        stub_function('is_admin')->will_return(false);
        list($query, $querySet) = createFreshQueryAndSpy();
        new Calendar();

        do_action('pre_get_posts', $query);

        expect_spy($querySet)->to_have_been_called->with('meta_key', 'sort_date');
        expect($querySet)->was_called()->toBeTrue();
    });

    it('will handle the default archive on the pre_get_posts hook', function() {
        $calendar = new Calendar();

        expect(has_action('pre_get_posts', [$calendar, 'customise_default_event_archive']))->toBeTruthy();
    });

    it('will handle date archives on the pre_get_posts hook', function() {
        $calendar = new Calendar();

        expect(has_action('pre_get_posts', [$calendar, 'handle_date_archives']))->toBeTruthy();
    });

    describe('default event archive', function() {
        it('does not apply any filtering to the admin query', function() {
            stub_function('is_admin')->will_return(true);
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            expect($querySet)->was_called_with('meta_query', any())->toBeFalse();
        });

        it('does not affect the query for another post type', function() {
            list($query, $querySet) = createFreshQueryAndSpy();
            $query->add_method('get')->when_called->with('post_type')->will_return('page');

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            expect($querySet)->was_called()->toBeFalse();

            // Restore the post_type to 'event' for other tests or else this pollutes the shared $query object
            $query->add_method('get')->when_called->with('post_type')->will_return('event');
        });

        // Note: testing actual filtered results is done in integration tests
        it('applies filtering to the front-end query', function() {
            stub_function('is_admin')->will_return(false);
            list($query, $querySet) = createFreshQueryAndSpy();
            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            expect_spy($querySet)->to_have_been_called->with('meta_query', any());
            expect($querySet)->was_called()->toBeTrue();
        });

        it('queries for no posts if "show past events" is set to "never"', function() {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return('never');
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            $spyCalls = $querySet->get_called_functions();
            $lastCall = end($spyCalls);

            expect($lastCall->get_args()[0])->toEqual('post__in')
                ->and($lastCall->get_args()[1])->toEqual([]);
        });

        it('does not limit by post__in if "show past events" is set to an expected value other than "never"', function(?string $setting) {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return($setting);
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            $spyCalls = $querySet->get_called_functions();
            foreach ($spyCalls as $call) {
                expect($call->get_args()[0])->not->toEqual('post__in');
            }
        })->with([
            'always', 'past_year', 'current_year', null
        ]);

        it('adds a meta query to filter out future events based on sort date when when "show past events" is not set', function() {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return(null);
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            // Even with match_array I have a bad time with expect_spy and array values.
            $spyCalls = $querySet->get_called_functions();
            $last = end($spyCalls);
            $metaQuery = $last->get_args()[1];
            expect($metaQuery[0])->toEqual([
                'key'     => 'sort_date',
                'value'   => '2026-08-25',
                'compare' => '<',
                'type'    => 'DATE',
            ]);
        });

        it('adds a meta query to filter out future events based on sort date when "show past events" is set to "always"', function() {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return('always');
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            // Even with match_array I have a bad time with expect_spy and array values.
            $spyCalls = $querySet->get_called_functions();
            $last = end($spyCalls);
            $metaQuery = $last->get_args()[1];
            expect($metaQuery[0])->toEqual([
                'key'     => 'sort_date',
                'value'   => '2026-08-25',
                'compare' => '<',
                'type'    => 'DATE',
            ]);
        });

        it('adds a meta query to filter out future and previous year events when "show past events" is set to "current_year"', function() {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return('current_year');
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            $spyCalls = $querySet->get_called_functions();
            $last = end($spyCalls);
            $metaQuery = $last->get_args()[1];
            expect($metaQuery[0])->toEqual([
                'key'     => 'sort_date',
                'value'   => ['2026-01-01', '2026-12-31'],
                'compare' => 'BETWEEN',
                'type'    => 'DATE',
            ]);
        });

        it('adds a meta query to filter out future and events older than one year when "show past events" is set to "past_year"', function() {
            stub_function('is_admin')->will_return(false);
            stub_function('get_option')->when_called->with('options_show_past_events')->will_return('past_year');
            list($query, $querySet) = createFreshQueryAndSpy();

            $instance = new Calendar();
            $instance->customise_default_event_archive($query);

            $spyCalls = $querySet->get_called_functions();
            $last = end($spyCalls);
            $metaQuery = $last->get_args()[1];
            expect($metaQuery[0])->toEqual([
                'key'     => 'sort_date',
                'value'   => ['2025-08-25', '2026-08-25'],
                'compare' => 'BETWEEN',
                'type'    => 'DATE',
            ]);
        });
    });
});
