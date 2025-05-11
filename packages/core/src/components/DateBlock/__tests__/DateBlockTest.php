<?php
/** @noinspection PhpUnhandledExceptionInspection */
use Doubleedesign\Comet\Core\{DateBlock};

describe('DateBlock', function() {

    test('should handle DateTime object', function() {
        $date = new DateTime('2025-04-25');
        $dateBlock = new DateBlock(['date' => $date]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('25 April 2025');
    });

    test('should convert from YYYY-MM-DD', function() {
        $date = '2025-04-25';
        $dateBlock = new DateBlock(['date' => $date]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('25 April 2025');
    });

    test('should convert from timestamp', function() {
        $date = 1745569399;
        $dateBlock = new DateBlock(['date' => $date]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('25 April 2025');
    });

    test('should be empty if date format is invalid', function() {
        $date = '25-04-2025';
        $dateBlock = new DateBlock(['date' => $date]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('');
    });

    test('Show day and year', function() {
        $date = new DateTime('2025-04-25');
        $dateBlock = new DateBlock(['date' => $date, 'showDay' => true, 'showYear' => true]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('Friday, 25 April 2025');
    });

    test('Show year but not day', function() {
        $date = new DateTime('2025-04-25');
        $dateBlock = new DateBlock(['date' => $date, 'showDay' => false, 'showYear' => true]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('25 April 2025');
    });

    test('Show day but not year', function() {
        $date = new DateTime('2025-04-25');
        $dateBlock = new DateBlock(['date' => $date, 'showYear' => false, 'showDay' => true]);

        $formattedDate = $dateBlock->get_accessible_date_string();
        expect($formattedDate)->toBe('Friday, 25 April');
    });
});
