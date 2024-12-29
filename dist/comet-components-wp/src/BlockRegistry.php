<?php

namespace Doubleedesign\Comet\WordPress;

use Closure;
use RuntimeException;
use WP_Block;
use WP_Block_Type_Registry;

class BlockRegistry extends JavaScriptImplementation {

    function __construct() {
        parent::__construct();
        add_action('init', [$this, 'register_blocks'], 10);
        add_filter('allowed_block_types_all', [$this, 'set_allowed_blocks'], 10, 2);
       // add_action('init', [$this, 'override_core_block_rendering'], 20);
    }

    /**
     * Register custom blocks
     * @return void
     */
    function register_blocks(): void {
        // TODO: Add components as blocks here (e.g., PageHeader, Call-to-Action, etc.)
    }


    /**
     * Limit available blocks for simplicity
     * NOTE: This is not the only place a block may be explicitly allowed.
     * Most notably, ACF-driven custom blocks and page/post type templates may use/allow them directly.
     * Some core blocks also have child blocks that already only show up in the right context.
     *
     * @param $allowed_blocks
     *
     * @return array
     */
    function set_allowed_blocks($allowed_blocks): array {
        $all_block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
        // Read core block list from JSON file
        $core = json_decode(file_get_contents(__DIR__ . '/block-support.json'), true);
        // TODO: Custom block types registered in the active theme + parent theme
        $custom = [];
        // Plugin block types
        $plugin = array_filter($all_block_types, fn($block_type) => str_starts_with($block_type->name, 'ninja-forms/'));

        $result = array_merge(array_column($custom, 'name'), array_column($plugin, 'name'), // add core or plugin blocks here if:
            // 1. They are to be allowed at the top level
            // 2. They Are allowed to be inserted as child blocks of a core block (note: set custom parents for core blocks in addCoreBlockParents() in blocks.js if not allowing them at the top level)
            // No need to include them here if they are only being used in one or more of the below contexts:
            // 1. As direct $allowed_blocks within custom ACF-driven blocks and/or
            // 2. In a page/post type template defined programmatically and locked there (so users can't delete something that can't be re-inserted)
            // TODO: When adding post support, there's some post-specific blocks that may be useful
            $core['core']['supported']);

        return $result;
    }


    /**
     * Override core block render function and use Comet instead
     * @return void
     */
    function override_core_block_rendering(): void {
        $blocks = $this->get_allowed_blocks();
        $core_blocks = array_filter($blocks, fn($block) => str_starts_with($block, 'core/'));

        foreach ($core_blocks as $core_block) {
            // Check if the block type exists
            $block_type = WP_Block_Type_Registry::get_instance()->get_registered($core_block);

            if ($block_type) {
                // Unregister the original block
                unregister_block_type($core_block);

                // Manually set up the block settings array, avoiding private/protected properties
                // because they cause errors (time shall tell whether that's a problem)
                $settings = [
                    'title'            => $block_type->title,
                    'description'      => $block_type->description,
                    'category'         => $block_type->category,
                    'icon'             => $block_type->icon,
                    'keywords'         => $block_type->keywords,
                    'supports'         => $block_type->supports,
                    'attributes'       => $block_type->attributes,
                    'uses_context'     => $block_type->uses_context,
                    'provides_context' => $block_type->provides_context,
                    'example'          => $block_type->example,
                    'api_version'      => $block_type->api_version,
                    'textdomain'       => $block_type->textdomain,
                    'render_callback'  => self::render_block_callback($core_block)
                ];

                // Re-register the block with the original settings and new render callback
                register_block_type($core_block, $settings);
            }
        }
    }

    /**
     * Inner function for the override, to render a core block using a custom template
     * @param $block_name
     *
     * @return Closure
     */
    static function render_block_callback($block_name): Closure {
        return function ($attributes, $content, $block_instance) use ($block_name) {
            return self::render_block($block_name, $attributes, $content, $block_instance);
        };
    }

    /**
     * The function called inside render_block_callback
     * TODO: Allow theme to override block output by looking in the theme directory before loading the file from here
     *
     * This exists separately for better debugging - this way we see render_block() in Xdebug stack traces,
     * whereas if this returned the closure directly, it would show up as an anonymous function
     * @param string $block_name
     * @param array $attributes
     * @param string $content
     * @param WP_Block $block_instance
     *
     * @return string
     * @throws RuntimeException
     */
    static function render_block(string $block_name, array $attributes, string $content, WP_Block $block_instance): string {
        $block_name_trimmed = explode('/', $block_name)[1];
        $inner_blocks = $block_instance->parsed_block['innerBlocks'];

        // For group block, detect variation based on layout attributes
        if ($block_name_trimmed === 'group') {
            $layout = $attributes['layout'];
            echo '<pre>';
            echo print_r($layout, true);
            echo '</pre>';
            if ($layout['type'] === 'flex') {
                if (isset($layout['orientation']) && $layout['orientation'] === 'vertical') {
                    $variation = 'stack';
                }
                else {
                    $variation = 'row';
                }
            }
            else if ($layout['type'] === 'grid') {
                $variation = 'grid';
            }
            else {
                $variation = 'group';
            }

            $block_name_trimmed = $variation;
        }

        $file = __DIR__ . '/blocks/' . $block_name_trimmed . '.php';

        ob_start();
        // Check if the file exists in the child theme, then in the parent theme
        if (file_exists($file)) {
            // The variables are extracted to make them available to the included file
            extract(['attributes' => $attributes, 'content' => $content, 'innerComponents' => $inner_blocks]);
            // Include the file that renders the block
            include $file;
        }
        else {
            throw new RuntimeException('Block template not found: ' . $file);
        }
        return ob_get_clean();
    }


    /**
     * Utility function to get all allowed blocks after filtering functions have run
     * @return array
     */
    function get_allowed_blocks(): array {
        $all_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
        $allowed_blocks = apply_filters('allowed_block_types_all', $all_blocks);

        return array_values($allowed_blocks);
    }
}
