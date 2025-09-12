<?php

namespace Doubleedesign\CometCanvas\Classic;

class Fields {

    public function __construct() {
        add_filter('acf/load_field/name=content_modules', [$this, 'simplify_flexibles_for_posts']);
        add_filter('acf/load_field/name=content_modules', [$this, 'simplify_flexibles_for_blog_page']);
        add_filter('comet_acf_flexible_content_is_nested', [$this, 'declare_post_modules_nested'], 10, 3);
        add_filter('gettext', [$this, 'you_are_editing_page_for_posts_message']);
    }

    public function simplify_flexibles_for_posts($field): array {
        // Only apply this filter in admin/editing context because it otherwise somehow breaks get_field results
        if (!is_admin() && !wp_doing_ajax()) {
            return $field;
        }

        if (get_post_type() === 'post') {
            // Loop through all the layouts and remove the width field because single.php handles the container
            foreach ($field['layouts'] as $layout_key => $layout) {
                if (isset($layout['sub_fields'])) {
                    foreach ($layout['sub_fields'] as $sub_field_key => $sub_field) {
                        if ($sub_field['name'] === 'width') {
                            unset($field['layouts'][$layout_key]['sub_fields'][$sub_field_key]);
                        }
                    }
                    // Reindex the sub_fields array
                    $field['layouts'][$layout_key]['sub_fields'] = array_values($field['layouts'][$layout_key]['sub_fields']);
                }
            }

            if (isset($field['layouts']['layout_copy'])) {
                // Remove Heading from copy layout
                $field['layouts']['layout_copy']['sub_fields'] = array_filter(
                    $field['layouts']['layout_copy']['sub_fields'],
                    fn($sub_field) => $sub_field['name'] !== 'heading'
                );

                // Reindex the sub_fields array
                $field['layouts']['layout_copy']['sub_fields'] = array_values($field['layouts']['layout_copy']['sub_fields']);

                // Change WYSIWYG config in copy layout to full
                // (because there's JS configuring allowed heading levels and we want to allow H2s in this case)
                // $field['layouts']['layout_copy']['sub_fields'] = array_map(
                $wysiwyg_field_index = array_search(
                    'wysiwyg',
                    array_column($field['layouts']['layout_copy']['sub_fields'], 'type')
                );
                $field['layouts']['layout_copy']['sub_fields'][$wysiwyg_field_index]['toolbar'] = 'full';

            }

            // Remove the Page Header layout because that will be handled centrally for the blog
            if (isset($field['layouts']['layout_page-header'])) {
                unset($field['layouts']['layout_page-header']);
            }

            // Remove the Child Pages layout because it's not relevant for the blog
            if (isset($field['layouts']['layout_child-pages'])) {
                unset($field['layouts']['layout_child-pages']);
            }
        }

        return $field;
    }

    public function simplify_flexibles_for_blog_page($field): array {
        // Only apply this filter in admin/editing context because it otherwise somehow breaks get_field results
        if (!is_admin() && !wp_doing_ajax()) {
            return $field;
        }

        if (get_the_id() == get_option('page_for_posts')) {
            // Only enable the page header for the blog page
            $field['layouts'] = array_filter(
                $field['layouts'],
                fn($layout) => in_array($layout['key'], ['layout_page-header']),
            );

            // Remove the Heading field from the Page Header layout
            // and replace it with a message explaining that the heading will be derived from the post, category, etc
            if (isset($field['layouts']['layout_page-header'])) {
                $field['layouts']['layout_page-header']['sub_fields'] = array_filter(
                    $field['layouts']['layout_page-header']['sub_fields'],
                    fn($sub_field) => $sub_field['key'] !== 'field__page-header__heading'
                );

                // Add a message field to explain that the heading will be derived from the post, category, etc
                $message = [
                    'key'       => 'field_page_header_message',
                    'name'      => '',
                    'type'      => 'message',
                    'message'   => 'The heading shown will be determined by the current post, category, or other archive being viewed. The main blog landing page will use the title of this page.',
                    'new_lines' => 'wpautop',
                    'esc_html'  => 0,
                ];

                // Reindex the sub_fields array and add the message field at the start
                $field['layouts']['layout_page-header']['sub_fields'] = array_merge(
                    [$message],
                    array_values($field['layouts']['layout_page-header']['sub_fields'])
                );
            }
        }

        return $field;
    }

    /**
     * This theme's single.php wraps all single post content (except the page header) in a single container.
     * This filter makes all modules except the page header nested at render time, so they don't add their own container.
     *
     * @param  bool  $isNested
     * @param  string  $module_name
     * @param  $post_id
     *
     * @return bool
     */
    public function declare_post_modules_nested(bool $isNested, string $module_name, $post_id): bool {
        if (get_post_type($post_id) == 'post' && $module_name !== 'page_header') {
            return true;
        }

        return $isNested;
    }

    public function you_are_editing_page_for_posts_message($translated_text): string {
        if ($translated_text === 'You are currently editing the page that shows your latest posts.') {
            $translated_text = 'You are currently editing the landing page for your blog, news, or articles section. Content modules here will be used across all archives and posts.';
        }

        return $translated_text;
    }

}
