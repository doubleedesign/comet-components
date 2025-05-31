<?php
use Doubleedesign\Comet\Core\{Config, Icon};

/**
 * Function to create a generic component class that uses the trait
 * allowing it to stay local to this test/file
 */
function create_component_with_icon(array $attributes): object {
    return new class($attributes) {
        use Icon;

        public function __construct(array $attributes) {
            $this->set_icon_from_attrs($attributes);
        }

        public function get_icon_prefix(): string {
            return $this->iconPrefix;
        }

        public function get_icon(): ?string {
            return $this->icon;
        }
    };
}

describe('Icon', function() {

    test('sets valid icon from "icon" attribute', function() {
        $component = create_component_with_icon(['icon' => 'check']);

        expect($component->get_icon())->toBe('check');
    });

    test('sets null icon when "icon" is null', function() {
        $component = create_component_with_icon(['icon' => null]);

        expect($component->get_icon())->toBeNull();
    });

    test('sets custom icon prefix', function() {
        $component = create_component_with_icon(['iconPrefix' => 'fa-light', 'icon' => 'check']);

        expect($component->get_icon_prefix())->toBe('fa-light');
    });

    test('uses default icon prefix from config', function() {
        Mockery::mock(Config::class)
            ->shouldReceive('get_icon_prefix')
            ->andReturn('fa-solid');
        $component = create_component_with_icon(['icon' => 'check']);

        expect($component->get_icon_prefix())->toBe('fa-solid');
    });
});
