<?php
namespace Doubleedesign\Comet\TestUtils\WordPress;
// when() is ultimately a wrapper for Patchwork\redefine() but it has nice syntax and compatibility with PHPUnit
use function Brain\Monkey\Functions\when;
use function Patchwork\redefine;
use function Patchwork\relay;

/**
 * Setup for test cases that test interactions/integrations with WordPress blocks.
 * Used by WpBridgeTestCase, the base class for tests involving WordPress block implementations of Comet Components.
 */
class WpBridgeTestSetup {

    public static function setUpBeforeClass(): void {
        // Patch some core PHP functions if necessary, before anything else
        // Patchwork needs to be explicitly loaded here, prior to BrainMonkey loading it, or else the redefine function is not available
        require_once __DIR__ . '/../../../vendor/antecedent/patchwork/Patchwork.php';
        self::patch_core_php_functions();

        $includesPath = self::get_wp_includes_path();

        // Import some things before BrainMonkey's patches
        // to minimise the amount of functions that need to be copied just because of redeclaration issues
        require_once "$includesPath/formatting.php";
    }

    /**
     * Function to set up before each test
     * @return void
     */
    public function setUp(): void {
        // Constants used within WordPress includes
        if(!defined('ABSPATH')) {
            define('ABSPATH', $this->get_abspath());
        }
        if(!defined('WPINC')) {
            define('WPINC', '/wp-includes');
        }
        if(!defined('SCRIPT_DEBUG')) {
            define('SCRIPT_DEBUG', false);
        }

        // Some commonly required patching of WordPress functions
        $this->patch_get_option();
        $this->patch_wp_includes_functions_php();

        // Block rendering dependencies
        $this->include_block_dependencies();

        // Custom/additional patching of WordPress functions
        // that need to be patched AFTER the block dependencies are loaded
        $this->patch_misc_wp_functions();
    }


    /**
     * Patch some core PHP functions that have usages
     * in WordPress files that cause issues in this standalone testing setup
     * Note: This needs to be done through Patchwork directly (not via BrainMonkey wrappers)
     * and the function(s) need to be listed in 'redefinable-internals' in patchwork.json
     *
     * @return void
     */
    private static function patch_core_php_functions(): void {

        redefine('require', function ($file) {
            $ignoreFiles = [
                // Files to ignore from wp-includes/blocks/index.php
                'legacy-widget.php',
                'widget-group.php',
                'require-static-blocks.php',
                'require-dynamic-blocks.php'
            ];

            $resolvedPath = realpath($file) ?: $file;
            if (is_dir($resolvedPath)) {
                return null;
            }

            $filename = basename($resolvedPath);
            if (in_array($filename, $ignoreFiles)) {
                return null;
            }

            // Proceed as normal for all other require calls
            return relay($file);
        });
    }


    /**
     * Get the absolute path to the directory WordPress is in
     * @return string
     */
    private function get_abspath(): string {
        return str_replace(
            '\\',
            '/',
            dirname(__DIR__, 3) . '\wordpress'
        );
    }


    /**
     * Get the absolute path to the wp-includes directory
     * @return string
     */
    public static function get_wp_includes_path(): string {
        return str_replace(
            '\\',
            '/',
            dirname(__DIR__, 3) . '\wordpress\wp-includes'
        );
    }

    /**
     * Patch get_option()
     * TODO: Load relevant test option values from a file or something
     * @return void
     */
    private function patch_get_option(): void {
        when('get_option')->alias(function(string $option, $default = false) {
            return match ($option) {
                'blog_charset' => 'UTF-8',
                default => $default,
            };
        });
    }


    /**
     * Patch various WordPress functions that are used in block rendering
     * but aren't already imported or patched by BrainMonkey or the imports in this file
     * @return void
     */
    private function patch_misc_wp_functions(): void  {
        when('add_action')->alias(function($hook, $callback) {
            // Just call the callback immediately
            call_user_func($callback);
        });

        when('add_filter')->alias(function($hook, $callback) {
            // Actually implement filter handling for block rendering
            global $wp_filter;
            if (!isset($wp_filter[$hook])) {
                $wp_filter[$hook] = [];
            }
            $wp_filter[$hook][] = $callback;
        });

        when('apply_filters')->alias(function($hook, ...$args) {
            // Basic filter application
            global $wp_filter;
            if (isset($wp_filter[$hook])) {
                foreach ($wp_filter[$hook] as $callback) {
                    $args[0] = call_user_func_array($callback, $args);
                }
            }
            return $args[0];
        });

        when('wp_get_theme')->justReturn((object) ['supports' => []]);
        when('wp_get_wp_version')->justReturn('6.7.1');

        when('current_theme_supports')->justReturn(true);
        when('wp_style_is')->justReturn(false);
        when('is_multisite')->justReturn(false);
        when('is_utf8_charset')->justReturn(true);
        when('includes_url')->justReturn($this->get_wp_includes_path());
        when('wp_should_load_separate_core_block_assets')->justReturn(true);

        // TODO: Probably want to implement these
        when('wp_enqueue_style')->justReturn();
        when('wp_enqueue_script')->justReturn();
        when('wp_register_style')->justReturn();
        when('wp_get_global_stylesheet')->justReturn('');
        when('wp_get_global_styles')->justReturn([]);
        when('wp_get_global_settings')->justReturn([]);
    }


    /**
     * Copy/patch some functions from wp-includes/functions.php because importing that whole file is problematic
     * @return void
     * @noinspection t
     */
    private function patch_wp_includes_functions_php(): void {
        when('wp_parse_args')->alias(function ($args, $defaults) {
            if (is_object($args)) {
                $parsed_args = get_object_vars($args);
            }
            elseif (is_array($args)) {
                $parsed_args = &$args;
            }
            else {
                wp_parse_str($args, $parsed_args);
            }

            if (is_array($defaults) && $defaults) {
                return array_merge($defaults, $parsed_args);
            }
            return $parsed_args;
        });

        when('wp_normalize_path')->alias(function ($path) {
            return $path;
        });

        when('wp_json_file_decode')->alias(function ($file) {
            return json_decode(file_get_contents($file), true);
        });

        when('_wp_array_get')->alias(function ($input_array, $path, $default_value = null ) {
            // Confirm $path is valid.
            if ( ! is_array( $path ) || 0 === count( $path ) ) {
                return $default_value;
            }

            foreach ( $path as $path_element ) {
                if ( ! is_array( $input_array ) ) {
                    return $default_value;
                }

                if ( is_string( $path_element )
                    || is_integer( $path_element )
                    || null === $path_element
                ) {
                    /*
                     * Check if the path element exists in the input array.
                     * We check with `isset()` first, as it is a lot faster
                     * than `array_key_exists()`.
                     */
                    if ( isset( $input_array[ $path_element ] ) ) {
                        $input_array = $input_array[ $path_element ];
                        continue;
                    }

                    /*
                     * If `isset()` returns false, we check with `array_key_exists()`,
                     * which also checks for `null` values.
                     */
                    if ( array_key_exists( $path_element, $input_array ) ) {
                        $input_array = $input_array[ $path_element ];
                        continue;
                    }
                }

                return $default_value;
            }

            return $input_array;
        });

        when('_canonical_charset')->justReturn('UTF-8');
    }


    /**
     * Include the necessary PHP dependencies for block rendering
     * @return void
     */
    private function include_block_dependencies(): void {
        $includesPath = $this->get_wp_includes_path();

        // Files required for WordPress blocks to work
        require_once "$includesPath/kses.php";
        require_once "$includesPath/rest-api.php"; // contains schema validation functions required for block registration
        require_once "$includesPath/class-wp-block-supports.php";
        require_once "$includesPath/block-supports/utils.php";
        //require_once "$includesPath/block-supports/colors.php";
        //require_once "$includesPath/block-supports/typography.php";
        //require_once "$includesPath/block-supports/border.php";
        //require_once "$includesPath/block-supports/spacing.php";
        require_once "$includesPath/block-supports/elements.php";
        require_once "$includesPath/block-supports/layout.php";
        require_once "$includesPath/block-supports/align.php";
        require_once "$includesPath/html-api/class-wp-html-attribute-token.php";
        require_once "$includesPath/html-api/class-wp-html-text-replacement.php";
        require_once "$includesPath/html-api/class-wp-html-tag-processor.php";
        require_once "$includesPath/class-wp-block-type-registry.php";
        require_once "$includesPath/class-wp-block-list.php";
        require_once "$includesPath/class-wp-block.php";
        require_once "$includesPath/class-wp-block-metadata-registry.php";
        require_once "$includesPath/class-wp-block-parser-block.php";
        require_once "$includesPath/class-wp-block-parser-frame.php";
        require_once "$includesPath/class-wp-block-type.php";
        require_once "$includesPath/class-wp-block-parser.php";
        require_once "$includesPath/blocks.php";
        require_once "$includesPath/blocks/index.php";
    }

}
