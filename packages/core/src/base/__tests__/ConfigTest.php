<?php
use Doubleedesign\Comet\Core\Config;

describe('Config', function() {

    it('is a singleton', function() {
        $instance1 = Config::getInstance();
        $instance2 = Config::getInstance();

        expect($instance1)->toBe($instance2); // toBe asserts the same object, toEqual would not
    });

    describe('component defaults', function() {
        beforeEach(function() {
            $instance = Config::getInstance();
            $instance->set_component_defaults('CallToAction', [
                'colorTheme' => 'secondary'
            ]);
        });

        it('returns component defaults from PascalCase name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('CallToAction');

            expect($defaults)->toEqual(['colorTheme' => 'secondary']);
        });

        it('returns component defaults from kebab-case name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('call-to-action');

            expect($defaults)->toEqual(['colorTheme' => 'secondary']);
        });

        it('returns component defaults from snake_case name', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('call_to_action');

            expect($defaults)->toEqual(['colorTheme' => 'secondary']);
        });

        it('returns an empty array if component defaults are not set', function() {
            $instance = Config::getInstance();
            $defaults = $instance->get_component_defaults('UnknownComponent');

            expect($defaults)->toEqual([]);
        });

    });

    describe('theme colours', function() {
        it('appends new theme colours to the default ones', function() {
            $instance = Config::getInstance();
            $instance->set_theme_colours([
                'info'      => '#ffff00'
            ]);
            $colours = $instance->get_theme_colours();

            expect($colours)->toEqual([
                'black'     => '#000000',
                'white'     => '#FFFFFF',
                'info'      => '#ffff00'
            ]);
        });

        it('replaces existing theme colours when set again (no duplicate keys)', function() {
            $instance = Config::getInstance();
            $default = $instance->get_theme_colours();
            expect($default)->toHaveKey('black', '#000000');

            $instance->set_theme_colours([
                'black'     => '#111111',
            ]);
            $colours = $instance->get_theme_colours();

            expect($colours)->toHaveKey('black', '#111111')
                ->and($colours)->not->toHaveKey('black', '#000000');
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
