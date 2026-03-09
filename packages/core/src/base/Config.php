<?php
namespace Doubleedesign\Comet\Core;
use Exception;
use InvalidArgumentException;

/**
 * Singleton class to manage global configuration settings for Comet components.
 */
class Config {
    private static ?self $instance = null;
    private ThemeColor $global_background = ThemeColor::WHITE;
    private string $icon_prefix = 'fa-solid';
    private array $blade_component_paths = [];
    private array $component_defaults = [
        'call-to-action' => ['colorTheme' => 'white', 'innerBackground' => 'primary', 'backgroundColor' => 'white'],
        'page-header'    => ['colorTheme' => 'primary'],
        'site-footer'    => ['backgroundColor' => 'dark', 'hAlign' => 'center']
    ];
    private array $theme_colours = [
        'white' => '#FFFFFF',
        'black' => '#000000',
    ];
    private array $theme_colour_pairs = [];

    public static function init(): void {
        if (!defined('COMET_VERSION')) {
            define('COMET_VERSION', '0.7.0');
        }

        if (self::$instance === null) {
            self::$instance = new self();
        }
    }

    private function get_config(): array {
        return [
            'global_background'     => $this->global_background,
            'icon_prefix'           => $this->icon_prefix,
            'blade_component_paths' => $this->blade_component_paths,
            'component_defaults'    => $this->component_defaults,
            'theme_colours'         => $this->theme_colours,
            'theme_colour_pairs'    => $this->theme_colour_pairs,
        ];
    }

    /**
     * Get the singleton instance of the Config class, instantiating it if it doesn't already exist.
     *
     * @return self
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::init();
        }

        return self::$instance;
    }

    // Prevent cloning and unserialization
    public function __clone() {
        throw new Exception("Comet Components core config: Cannot clone singleton");
    }
    public function __wakeup() {
        throw new Exception("Comet Components core config: Cannot unserialize singleton");
    }

    public function get(string $key): mixed {
        if (!isset($this->get_config()[$key])) {
            throw new InvalidArgumentException("Comet Components core config: Invalid config key '$key'");
        }

        return $this->get_config()[$key] ?? null;
    }

    public function has(string $key): bool {
        return isset($this->config[$key]);
    }

    public function all(): array {
        return $this->config;
    }

    // Convenience methods for common operations
    public function get_global_background(): string {
        return $this->get('global_background')->value;
    }

    public function set_global_background(string|ThemeColor $color): void {
        if ($color instanceof ThemeColor) {
            $this->global_background = $color;
        }
        else {
            $color = ThemeColor::tryFrom($color) ?? ThemeColor::WHITE;
            $this->global_background = $color;
        }
    }

    /**
     * Set global theme colours from the provided array and generate base set of pairs with contrast validation
     *
     * @param  array  $colours  - colorName:hexValue pairs
     *
     * @return void
     */
    public function set_theme_colours(array $colours): void {
        $this->theme_colours = array_merge($this->theme_colours, $colours);
    }

    public function clear_theme_colour_pairs(): void {
        $this->theme_colour_pairs = [];
    }

    /**
     * @param  array  $pairs  - array of arrays with two values, the first being the desired foreground and the second being the background
     *
     * @return void
     */
    public function maybe_add_theme_colour_pairs(array $pairs): void {
        // If one pair was passed as a flat array, convert it to a nested array
        if (count($pairs) === 2 && is_string($pairs[0]) && is_string($pairs[1])) {
            $pairs = [[$pairs[0], $pairs[1]]];
        }

        foreach ($pairs as $pair) {
            $this->maybe_add_theme_colour_pair($pair[0], $pair[1]);
        }
    }

    private function maybe_add_theme_colour_pair(string $foreground, string $background, ?float $threshold = 3): void {
        // Check if the pair already exists
        $exists = array_find($this->theme_colour_pairs, function($pair) use ($background, $foreground) {
            return $pair['background'] === $background && $pair['foreground'] === $foreground;
        });
        if ($exists !== null) {
            //error_log("Comet Components core config: Colour pair foreground '$foreground' and background '$background' already exists so has not been registered again.");

            return;
        }

        // Check if there is sufficient contrast
        $valid = ColorUtils::validate_pair($foreground, $background, $threshold);
        if ($valid) {
            $this->theme_colour_pairs[] = ['foreground' => $foreground, 'background' => $background];
        }
        else {
            $message = "Comet Components core config: Colour pair foreground '$foreground' and background '$background' does not meet contrast threshold of $threshold:1 so has not been registered.";
            //error_log($message);
        }
    }

    public function get_theme_colours(): array {
        return $this->theme_colours;
    }

    public function get_theme_colour_pairs(): array {
        return $this->theme_colour_pairs;
    }

    public function set_icon_prefix($prefix): void {
        $this->icon_prefix = $prefix;
    }

    public function get_icon_prefix(): string {
        return $this->icon_prefix;
    }

    public function get_component_defaults(string $component): array {
        $defaults = $this->get('component_defaults');
        $componentName = Utils::kebab_case($component);

        return $defaults[$componentName] ?? [];
    }

    public function set_component_defaults(string $component, array $settings): void {
        $defaults = $this->get('component_defaults');
        $componentName = Utils::kebab_case($component);

        $this->component_defaults[$componentName] = array_merge($defaults[$componentName] ?? [], $settings);
    }

    /**
     * Option to specify directories containing "components" folders with Blade component templates to override the ones provided by Comet.
     * The structure of that directory should mirror that of the Comet core package's components directory so the templates can be found automatically.
     * Do not include "components" in the path.
     *
     * @param  array  $paths
     *
     * @return void
     */
    public function set_blade_component_paths(array $paths): void {
        $this->blade_component_paths = array_unique(array_merge($this->blade_component_paths, $paths));
    }
}
