<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\{DateRangeBlock};

describe('DateRangeBlock', function() {

    test('Date and month in same month', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-06-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('13-15 June');
    });

    test('Date and month in different years', function() {
        $startDate = new DateTime('2025-12-20');
        $endDate = new DateTime('2026-01-05');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => true, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('20 December 2025 - 5 January 2026');
    });

    test('Date and month in different years, without showing year', function() {
        $startDate = new DateTime('2025-12-20');
        $endDate = new DateTime('2026-01-05');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => false, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('20 December - 5 January');
    });

    test('Day, date and month in same month', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-06-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => false, 'showDay' => true]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('Friday 13 - Sunday 15 June');
    });

    test('Date and month with different months', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-07-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => false, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('13 June - 15 July');
    });

    test('Day, date and month with different months', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-07-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => false, 'showDay' => true]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('Friday 13 June - Tuesday 15 July');
    });

    test('Date and month in same month, with year', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-06-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => true, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('13-15 June 2025');
    });

    test('Date and month with different months, with year', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2025-07-15');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => true, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('13 June - 15 July 2025');
    });

    test('Date, month, and year with different years', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2026-09-07');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => true, 'showDay' => false]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('13 June 2025 - 7 September 2026');
    });

    test('Day, date, month, and year with different months and years', function() {
        $startDate = new DateTime('2025-06-13');
        $endDate = new DateTime('2026-08-02');
        $dateRangeBlock = new DateRangeBlock(['startDate' => $startDate, 'endDate' => $endDate, 'showYear' => true, 'showDay' => true]);

        $formattedDate = $dateRangeBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('Friday 13 June 2025 - Sunday 2 August 2026');
    });
});
