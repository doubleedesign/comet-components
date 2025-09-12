<?php
namespace Doubleedesign\CometCanvas\Classic;
use Exception;

if (!file_exists(WP_PLUGIN_DIR . '/comet-plugin-acf/src/Fields.php')) {
    error_log('Shared content error: Comet Canvas Classic Shared Content module requires the Comet ACF plugin to be installed and active.');

    return;
}

require_once WP_PLUGIN_DIR . '/comet-plugin-acf/src/Fields.php';
require_once WP_PLUGIN_DIR . '/comet-plugin-acf/src/TemplateHandler.php';

class SharedContent extends \Doubleedesign\Comet\WordPress\Classic\Fields {

    public function __construct() {
        // parent::__construct(); // We don't actually want to call this because that would re-initialise the plugin fields
        // we're just extending the class to get access to its module configurations
        add_action('acf/init', [$this, 'register_admin_page']);
        add_action('acf/include_fields', [$this, 'use_comet_flexibles_for_shared_content'], 20);
        add_filter('acf/fields/flexible_content/no_value_message', [$this, 'customise_no_value_message'], 15, 2);
    }

    public function customise_no_value_message($message, $field): string {
        if ($field['key'] === 'field_shared-content-modules') {

            return sprintf(
                __('Click the "%s" button to add a section to the content of every page that uses the default page template (except the homepage)', 'comet'),
                $field['button_label']
            );
        }

        return $message;
    }

    public function register_admin_page(): void {
        acf_add_options_page(array(
            'page_title'  => 'Shared Content',
            'menu_title'  => 'Shared Content',
            'menu_slug'   => 'shared-content',
            'description' => '',
            'capability'  => 'edit_posts',
            'redirect'    => false,
            'position'    => 21,
            'icon_url'    => 'dashicons-format-aside'
        ));
    }

    public function use_comet_flexibles_for_shared_content(): void {
        $modules = $this->get_basic_modules();
        $site_name = get_bloginfo('name');

        // Remove some modules that don't make sense to append to pages
        unset($modules['layout_page-header']);
        unset($modules['layout_banner']);
        unset($modules['layout_gallery']);
        unset($modules['layout_image']);
        unset($modules['layout_copy']);

        $fields = array(
            'key'                    => 'group_shared-content-modules',
            'title'                  => 'Appended content',
            'description'            => 'Content to append to every page that uses the default page template (except the homepage)',
            'fields'                 => array(
                array(
                    'key' 			 => 'field_shared-content-info',
                    'name'    => 'appended_content_info',
                    'type'    => 'message',
                    'message' => "Looking for footer content such as contact details or social media links? These can be edited in the <a href='/wp-admin/admin.php?page=acf-options-global-options'>{$site_name} settings</a>.",
                ),
                array(
                    'key'               => 'field_shared-content-modules',
                    'label'             => 'Content modules',
                    'name'              => 'appended_content_modules',
                    'instructions'      => 'These sections will be added to the end of the content of every page that uses the default page template (except the homepage, which has a special template not selectable in the admin). This enables you to define and edit them in one place, and have them be the same across the site automatically.',
                    'type'              => 'flexible_content',
                    'layouts'           => $modules,
                    'button_label'      => 'Add section',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'shared-content',
                    ),
                ),
            ),
            'menu_order'             => 0,
            'position'               => 'normal',
            'style'                  => 'default',
            'label_placement'        => 'top',
            'instruction_placement'  => 'label',
            'active'                 => true,
            'show_in_rest'           => 0,
            'modified'               => 1757583077
        );

        acf_add_local_field_group($fields);
    }

    /**
     * Render the shared appended content modules defined in the ACF options page for Shared Content.
     * TODO: Consider adding some logic to this for where these can be used.
     *
     * @return void
     */
    public static function render_appended_content(): void {
        if (!function_exists('get_row_layout')) {
            return;
        }

        if (have_rows('appended_content_modules', 'option')) {
            while (have_rows('appended_content_modules', 'option')) {
                the_row();
                $layout = get_row_layout();
                $fields = get_row(true);
                // This only does one level.
                // If modules with nested modules are added to the appended module options, this will need to be adjusted

                $template_path = \Doubleedesign\Comet\WordPress\Classic\TemplateHandler::get_template_path($layout);
                try {
                    if ($template_path) {
                        include $template_path;
                    }
                }
                catch (Exception $e) {
                    echo '<!-- ' . esc_html($e->getMessage()) . ' -->';
                }
            }
        }
    }
}
