<?php

namespace Doubleedesign\Comet\WordPress\Calendar;

class QuickEdit {

    public function __construct() {
        add_action('admin_head', 'acf_form_head', 5);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_js'], 20);
        add_filter('script_loader_tag', [$this, 'script_type_module'], 10, 3);
        add_filter('manage_event_posts_custom_column', [$this, 'populate_admin_list_columns'], 30, 2);
        add_action('acf/save_post', [$this, 'handle_inline_acf_form_submit'], 11);
    }

    public function enqueue_admin_js(): void {
        $js_path = plugin_dir_url(__FILE__) . 'quick-edit.js';
        wp_enqueue_script('comet-calendar-admin-quick-edit', $js_path, [], COMET_CALENDAR_VERSION, true);
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
     * Populate the custom columns in the admin list
     *
     * @param  $column_name
     * @param  $post_id
     *
     * @return void
     * @throws Exception
     */
    public function populate_admin_list_columns($column_name, $post_id): void {

        /**
         * Using acf_form() here is not a standard/expected use of it, and the post list itself is also a form
         * which makes adding the ACF forms inside less than ideal semantically but much easier than something more custom.
         * But for some unknown reason, only the second and subsequent ACF forms are actually <form>s,
         * so this one is here as a hidden decoy to make the real ones for location and date work.
         */
        if ($column_name === 'hacky_extra') {
            echo '<div style="display:none;">';
            acf_form(array(
                'id'      => 'acf-form-decoy',
                'post_id' => $post_id,
                'fields'  => array('not_a_real_field'),
                'form'    => true,
                'ajax'    => false,
                'return'  => ''
            ));
            echo '</div>';
        }

        if ($column_name === 'event_date') {
            // Date type is a select list with values that should match up to the names of the groups that contain the detailed data
            $date_type = get_field('type');
            $date_data = get_field($date_type);
            $field = get_field_object($date_type, $post_id);
            $field_key = $field['key'];

            $output = '';
            switch ($date_type) {
                case 'single':
                    $output = $date_data['date'];
                    break;
                case 'range':
                    $output = $date_data['start_date'] . ' - ' . $date_data['end_date'];
                    break;
                case 'multi':
                    foreach ($date_data['dates'] as $date) {
                        $output .= $date['date'] . '<br>';
                    }
                    break;
                case 'multi_extended':
                    foreach ($date_data as $date) {
                        $output .= $date['date'] . '<br>';
                    }
                    break;
            }

            echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$output</span>
			HTML;

            $form_id = 'acf-form-event-date-' . $post_id;
            $this->display_wrapped_acf_form($form_id, $post_id, ['type', 'single', 'range', 'multi', 'multi_extended']);
        }

        if ($column_name === 'location') {
            $field = get_field_object('location', $post_id);
            $field_key = $field['key'];
            $value = get_post_meta($post_id, 'location', true);

            // Display the field value wrapped in ID and field key identifiers so the JS can update it when inline edits are saved
            echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$value</span>
			HTML;

            $form_id = 'acf-form-location-' . $post_id;
            $this->display_wrapped_acf_form($form_id, $post_id, ['location']);
        }

        if ($column_name === 'external_link') {
            $field = get_field_object('external_link', $post_id);

            $field_key = $field['key'];
            $value = get_post_meta($post_id, 'external_link', true);
            $link = '';
            if ($value) {
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Pro 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2025 Fonticons, Inc.--><path d="M336 0c-8.8 0-16 7.2-16 16s7.2 16 16 16l121.4 0L212.7 276.7c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L480 54.6 480 176c0 8.8 7.2 16 16 16s16-7.2 16-16l0-160c0-8.8-7.2-16-16-16L336 0zM64 32C28.7 32 0 60.7 0 96L0 448c0 35.3 28.7 64 64 64l352 0c35.3 0 64-28.7 64-64l0-144c0-8.8-7.2-16-16-16s-16 7.2-16 16l0 144c0 17.7-14.3 32-32 32L64 480c-17.7 0-32-14.3-32-32L32 96c0-17.7 14.3-32 32-32l144 0c8.8 0 16-7.2 16-16s-7.2-16-16-16L64 32z"/></svg>';
                $link = '<a class="external-link" href="' . esc_url($value['url']) . '" target="_blank" rel="noopener noreferrer">' . esc_html($value['title']) . $icon . '</a>';
            }

            // Display the field value wrapped in ID and field key identifiers so the JS can update it when inline edits are saved
            echo <<<HTML
			<span class="acf-field-value" data-field-key="$field_key" data-post-id="$post_id">$link</span>
			HTML;

            $form_id = 'acf-form-external-link-' . $post_id;
            $this->display_wrapped_acf_form($form_id, $post_id, ['external_link']);
        }
    }

    /**
     * Additional handling for the AJAX form submission from the inline ACF forms in the admin list
     * ACF takes care of the actual data save, this just sends a JSON response back to the JavaScript rather than the whole page HTML
     *
     * @param  $post_id
     *
     * @return void
     */
    public function handle_inline_acf_form_submit($post_id): void {
        if (isset($_POST['custom_acf_inline_form']) && $_POST['custom_acf_inline_form'] == 1) {
            wp_send_json_success([
                'post_id' => $post_id,
                'fields'  => $_POST['acf'] ?? [],
            ]);

            wp_die();
        }
    }

    private function display_wrapped_acf_form($formId, $postId, $fields): void {
        echo <<<HTML
		<div class="row-actions">
			<span class="inline hide-if-no-js">
				<button class="button-link button-link--acf" aria-controls="$formId">Quick edit</button>
			</span>
		</div>
		<div class="admin-column-acf-form" data-form-id="$formId">
		HTML;

        acf_form(array(
            'id'                 => $formId,
            'post_id'            => $postId,
            'form'               => true,
            'form_attributes'    => array(
                'method' => 'post',
            ),
            'fields'             => $fields,
            'html_before_fields' => '<div class="acf-spinner"></div>',
            'html_after_fields'  => '<button class="button cancel" type="reset">Cancel</button>',
            'ajax'               => true, // Note: ACF's AJAX doesn't fully work in this context, see form submission functions below and admin.js for custom handling
            'return'             => '',
        ));

        echo <<<HTML
		</div>
		HTML;

    }

}
