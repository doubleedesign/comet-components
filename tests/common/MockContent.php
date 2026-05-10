<?php
namespace Doubleedesign\Comet\TestUtils;
use Doubleedesign\Comet\Core\ThemeColor;
use Faker\Factory;

class MockContent {

    public static function generate_paragraph($length = 'medium'): string {
        $faker = Factory::create();

        if ($length === 'short') {
            return $faker->realTextBetween(100, 150);
        }
        if ($length === 'long') {
            return $faker->realTextBetween(300, 500);
        }

        return $faker->realTextBetween(200, 300);
    }

    public static function get_random_background_colour(): string {
        $colours = ThemeColor::cases();

        return $colours[array_rand($colours)]->value;
    }
}
