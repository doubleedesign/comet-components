<?php

namespace Doubleedesign\Comet\WordPress\Calendar;

class QuickAdd {

    public function __construct() {
        add_action('admin_head', 'acf_form_head', 5);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_js'], 10);
        add_filter('script_loader_tag', [$this, 'script_type_module'], 10, 3);
        add_filter('views_edit-event', [$this, 'display_quick_add_form'], 11);
        add_action('admin_notices', array($this, 'display_quick_add_success_message'), 10);
        add_action('acf/save_post', [$this, 'handle_acf_quick_add_form_submit'], 20);
    }

    public function enqueue_admin_js(): void {
        $js_path = plugin_dir_url(__FILE__) . 'quick-add.js';
        wp_enqueue_script('comet-calendar-admin-quick-add', $js_path, [], COMET_CALENDAR_VERSION, true);
    }

    /**
     * Add type=module to script tags
     *
     * @param  $tag
     * @param  $handle
     * @param  $src
     *
     * @return mixed|string
     */
    public function script_type_module($tag, $handle, $src): mixed {
        // If it already has the type="module" attribute, skip
        if (str_contains($tag, 'type="module"')) {
            return $tag;
        }

        if (str_starts_with($handle, 'comet-calendar-admin')) {
            return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '" ></script>';
        }

        return $tag;
    }

    /**
     * Add an ACF form at the top of the Events list in the admin
     * Note: This requires some JS to aid handling or we get a white screen on save, see admin.js
     *
     * @param  $views
     *
     * @return mixed
     */
    public function display_quick_add_form($views): mixed {

        // Copy as much of the HTML structure/classes etc. from ACF post meta boxes so we get the same styling
        $headerHtml = <<<HTML
		<div class="postbox-header">
			<h2>Quick Add</h2>
			<button type="button" class="handlediv" aria-expanded="true">
				<span class="screen-reader-text">Toggle panel: Quick Add</span>
				<span class="toggle-indicator" aria-hidden="true"></span>
			</button>
		</div>
		HTML;

        echo '<div id="poststuff">';
        echo '<div class="admin-quick-add postbox acf-postbox">';
        echo $headerHtml;
        acf_form(array(
            'id'                => 'acf-form-quick-add',
            'post_id'           => 'new_post',
            'post_title'        => true,
            'post_content'      => false,
            'new_post'          => array(
                'post_type'   => 'event',
                'post_status' => 'publish'
            ),
            'form'              => true,
            'form_attributes'   => array(
                'method' => 'post',
            ),
            'fields'            => array(
                'field__event__type',
                'field__event__date--single',
                'field__event__date--range',
                'field__event__date--multiple',
                'field__event__location',
                'field__event__link'
            ),
            'ajax'               => true, // Note: ACF's AJAX doesn't fully work in this context, see form submission functions below and admin.js for custom handling
            // Wrapping in a fieldset enables disabling the whole form in certain scenarios via JS
            'html_before_fields' => '<fieldset>',
            'html_after_fields'  => '</fieldset><button class="button cancel" type="reset">Cancel</button>',
            'submit_value'       => 'Add event',
            'return'             => '',
        ));
        echo '</div>';
        echo '</div>';

        return $views;
    }

    /**
     * Additional handling for the AJAX form submission from the ACF quick add form in the admin list
     * ACF takes care of the actual data save, this just sends a JSON response back to the JavaScript rather than the whole page HTML
     *
     * @param  $post_id
     *
     * @return void
     */
    public function handle_acf_quick_add_form_submit($post_id): void {
        if (isset($_POST['custom_acf_quick_add_form']) && $_POST['custom_acf_quick_add_form'] == 1) {
            // We only have access to the submitted data here, not the resulting post ID, but we can infer it with a query for the most recently added event
            // Note: The \ before WP_Query is very important, otherwise it looks for that class in the Comet Calendar namespace
            $query = new \WP_Query(array(
                'post_type'      => 'event',
                'posts_per_page' => 1,
                'orderby'        => 'post_date',
                'order'          => 'DESC',
            ));
            $post_id = $query->posts[0]->ID;
            wp_send_json_success([
                'post_id' => $post_id,
            ]);

            wp_die();
        }
    }

    /**
     * Display a success message after the quick add form is submitted and returns successfully
     *
     * @return void
     */
    public function display_quick_add_success_message(): void {
        if (!isset($_GET['post_type']) || $_GET['post_type'] !== 'event') return;
        if (!isset($_GET['added'])) return;

        $post_id = $_GET['added'];
        $post_title = get_the_title($post_id);

        echo <<<HTML
		<div class="notice notice-success is-dismissible comet-quick-add-success">
			<p>Event "$post_title" added successfully.</p>
		</div>
		HTML;

    }

}
