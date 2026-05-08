<?php

use Doubleedesign\Comet\Core\{Config, ThemeColor};
use function Spies\{stub_function, expect_spy, get_spy_for, match_pattern};

beforeEach(function() {
    // Mock a range of colour types
    // and values that we know will/won't meet contrast thresholds to test the colour pair functionality
    stub_function('file_get_contents')->when_called->will_return(
        <<<CSS
		:root {
			--color-primary: #4B0082;
			--color-secondary: rgb(30, 75, 222);
			--color-accent: hsl(51, 100%, 50%);
			--color-info: lch(65% 45 240);
			--color-warning: lab(75% 17.50 65);
			--color-dark: oklch(0.25 0 275);
			--color-light: ghostwhite;
			--color-invalidName: #8a1010;
		}
		CSS
    );
});

describe('Config', function() {

    it('is a singleton', function() {
        $instance1 = Config::getInstance();
        $instance2 = Config::getInstance();

        expect($instance1)->toBe($instance2); // toBe asserts the same object, toEqual would not
    });

    describe('component defaults', function() {
        beforeEach(function() {
            $instance = Config::getInstance();
            $instance->set_component_defaults('call-to-action', [
                'colorTheme' => 'secondary'
            ]);
        });

        it('returns component defaults from PascalCase name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('CallToAction');

            expect($defaults)->toMatchArray(['colorTheme' => 'secondary']);
        });

        it('returns component defaults from kebab-case name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('call-to-action');

            expect($defaults)->toMatchArray(['colorTheme' => 'secondary']);
        });

        it('returns component defaults from snake_case name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('call_to_action');

            expect($defaults)->toMatchArray(['colorTheme' => 'secondary']);
        });

        it('returns an empty array if component defaults are not set', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('UnknownComponent');

            expect($defaults)->toEqual([]);
        });

        it('overwrites an existing component default without clearing others', function() {
            $instance = Config::getInstance();
            $instance->set_component_defaults('call-to-action', [
                'colorTheme'      => 'primary',
                'backgroundColor' => 'dark'
            ]);
            $defaults = $instance->get_component_defaults('call-to-action');

            expect($defaults)->toMatchArray([
                'colorTheme'      => 'primary',
                'backgroundColor' => 'dark'
            ]);

            $instance->set_component_defaults('call-to-action', [
                'colorTheme' => 'info'
            ]);
            $defaults = $instance->get_component_defaults('CallToAction');

            expect($defaults)->toMatchArray([
                'colorTheme'      => 'info',
                'backgroundColor' => 'dark'
            ]);
        });

    });

    describe('theme colours', function() {
        it('replaces existing theme colours when set again (no duplicate keys)', function() {
            $instance = Config::getInstance();
            $default = $instance->get_theme_colours();
            expect($default)->toHaveKey('black', '#000000');

            stub_function('file_get_contents')->when_called->will_return(
                <<<CSS
				:root {
					--color-black: #111111;
				}
				CSS
            );
            $instance->set_theme_colours(
                ['black' => 'var(--color-black)']
            );

            $colours = $instance->get_theme_colours();

            expect($colours)->toHaveKey('black', 'var(--color-black)')
                ->and($colours)->not->toHaveKey('black', '#000000');
        });

        it('adds a colour pair if it has sufficient contrast', function() {
            $instance = Config::getInstance();
			$instance->clear_theme_colour_pairs();
            $instance->set_theme_colours([
                'secondary' => 'var(--color-secondary)',
                'light'     => 'var(--color-light)'
            ]);

            $instance->maybe_add_theme_colour_pairs([ThemeColor::SECONDARY->value, ThemeColor::LIGHT->value]);

            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toContain(['foreground' => 'secondary', 'background' => 'light']);
        });

        it('adds multiple colour pairs if all have sufficient contrast', function() {
            $instance = Config::getInstance();
	        $instance->clear_theme_colour_pairs();
	        $instance->set_theme_colours([
		        'primary'   => 'var(--color-primary)',
		        'secondary' => 'var(--color-secondary)',
		        'accent'    => 'var(--color-accent)',
		        'info'      => 'var(--color-info)',
		        'dark'      => 'var(--color-dark)'
	        ]);

            $instance->maybe_add_theme_colour_pairs([
                [ThemeColor::ACCENT->value, ThemeColor::PRIMARY->value],
                [ThemeColor::ACCENT->value, ThemeColor::SECONDARY->value],
            ]);

            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toContain(['foreground' => 'accent', 'background' => 'primary'])
                ->and($pairs)->toContain(['foreground' => 'accent', 'background' => 'primary']);
        });

        it('adds only the valid pairs if some have sufficient contrast and others do not', function() {
            $logSpy = get_spy_for('error_log');
            $instance = Config::getInstance();
	        $instance->clear_theme_colour_pairs();
            $instance->set_theme_colours([
                'accent' => 'var(--color-accent)',
                'info'   => 'var(--color-info)',
                'dark'   => 'var(--color-dark)'
            ]);

            $instance->maybe_add_theme_colour_pairs([
                [ThemeColor::ACCENT->value, ThemeColor::DARK->value],
                [ThemeColor::INFO->value, ThemeColor::ACCENT->value],
            ]);

            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toContain(['foreground' => 'accent', 'background' => 'dark']);

            expect_spy($logSpy)->to_have_been_called->with(match_pattern("/does not meet contrast threshold of 3:1 so has not been registered/"))->verify();
            expect($pairs)->not->toContain(['background' => 'accent', 'foreground' => 'info']);
        });

        it('appends a colour pair to the existing config', function() {
            $instance = Config::getInstance();
	        $instance->clear_theme_colour_pairs();
            $instance->set_theme_colours([
                'primary'   => 'var(--color-primary)',
                'secondary' => 'var(--color-secondary)',
                'accent'    => 'var(--color-accent)',
                'info'      => 'var(--color-info)',
                'dark'      => 'var(--color-dark)',
                'light'     => 'var(--color-light)'
            ]);

            $instance->maybe_add_theme_colour_pairs([ThemeColor::LIGHT->value, ThemeColor::PRIMARY->value]);
            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toContain(['foreground' => 'light', 'background' => 'primary']);

            // Add another pair
            $instance->maybe_add_theme_colour_pairs([ThemeColor::ACCENT->value, ThemeColor::DARK->value]);
            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toBe([
                ['foreground' => 'light', 'background' => 'primary'],
                ['foreground' => 'accent', 'background' => 'dark']
            ]);

        });

        it('does not re-add a colour pair that is already in the config', function() {
            $logSpy = get_spy_for('error_log');
            $instance = Config::getInstance();
	        $instance->clear_theme_colour_pairs();
            $instance->set_theme_colours([
                'primary'   => 'var(--color-primary)',
                'secondary' => 'var(--color-secondary)',
                'accent'    => 'var(--color-accent)',
                'info'      => 'var(--color-info)',
                'dark'      => 'var(--color-dark)'
            ]);

            $instance->maybe_add_theme_colour_pairs([ThemeColor::ACCENT->value, ThemeColor::PRIMARY->value]);
            $pairs = $instance->get_theme_colour_pairs();
            expect($pairs)->toContain(['foreground' => 'accent', 'background' => 'primary']);

            // Try to add the same pair again
            $instance->maybe_add_theme_colour_pairs([ThemeColor::ACCENT->value, ThemeColor::PRIMARY->value]);
            expect_spy($logSpy)->to_have_been_called->with(match_pattern("/Colour pair foreground 'accent' and background 'primary' already exists/"))->verify();
            $pairs = $instance->get_theme_colour_pairs();
            expect(count($pairs))->toEqual(1);
        });

        it('logs a warning and does not add a colour pair if they have insufficient contrast', function() {
            $logSpy = get_spy_for('error_log');
            $instance = Config::getInstance();

            $instance->maybe_add_theme_colour_pairs([ThemeColor::INFO->value, ThemeColor::DARK->value]);

            $pairs = $instance->get_theme_colour_pairs();

            expect_spy($logSpy)->to_have_been_called->with(match_pattern("/does not meet contrast threshold of 3:1 so has not been registered/"));
            expect($pairs)->not->toContain(['background' => 'dark', 'foreground' => 'info']);
        });
    });

    describe('global background', function() {
        it('returns the default global background', function() {
            $instance = Config::getInstance();
            $background = $instance->get_global_background();

            expect($background)->toBe('white');
        });

        it('sets a new global background', function() {
            $instance = Config::getInstance();
            $instance->set_global_background('dark');
            $background = $instance->get_global_background();

            expect($background)->toBe('dark');
        });

        it('does not set a new global background the colour name is invalid', function() {
            $instance = Config::getInstance();
            $instance->set_global_background('invalid-color-name');
            $background = $instance->get_global_background();

            expect($background)->toBe('white');
        });
    });

    describe('icon prefix', function() {
        it('returns the default icon prefix', function() {
            $instance = Config::getInstance();
            $prefix = $instance->get_icon_prefix();

            expect($prefix)->toBe('fa-solid');
        });

        it('sets a new icon prefix', function() {
            $instance = Config::getInstance();
            $instance->set_icon_prefix('custom-icon');
            $prefix = $instance->get_icon_prefix();

            expect($prefix)->toBe('custom-icon');
        });
    });

    describe('blade component paths', function() {
        it('returns an empty array by default', function() {
            $instance = Config::getInstance();
            $paths = $instance->get('blade_component_paths');

            expect($paths)->toEqual([]);
        });

        it('sets custom blade component paths', function() {
            $instance = Config::getInstance();
            $instance->set_blade_component_paths(['/path/to/components', '/another/path']);
            $paths = $instance->get('blade_component_paths');

            expect($paths)->toEqual(['/path/to/components', '/another/path']);
        });
    });

    it('cannot be cloned', function() {
        $instance = Config::getInstance();

        expect(function() use ($instance) {
            $clone = clone $instance;
        })->toThrow(new Exception("Comet Components core config: Cannot clone singleton"));
    });

    it('cannot be unserialized', function() {
        $instance = Config::getInstance();

        expect(function() use ($instance) {
            unserialize(serialize($instance));
        })->toThrow(new Exception("Comet Components core config: Cannot unserialize singleton"));
    });

    it('throws an exception if an invalid key is accessed', function() {
        $instance = Config::getInstance();

        expect(function() use ($instance) {
            $invalid = $instance->get('non_existent_key');
        })->toThrow(new InvalidArgumentException("Comet Components core config: Invalid config key 'non_existent_key'"));
    });

});
