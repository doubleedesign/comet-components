<?php
namespace Doubleedesign\CometCanvas\Classic;

class SectionMenuBuilder {
    private int $page_id;
    private int $top_level_page_id;
    private array $section_menu = [];

    public function __construct($page_id) {
        $this->page_id = $page_id;
        $page_ancestors = get_ancestors($this->page_id, 'page');
        $this->top_level_page_id = $page_ancestors ? end($page_ancestors) : $this->page_id;

        $this->build_section_menu();
    }

    public function get_menu(): array {
        return $this->section_menu;
    }

    protected function build_section_menu(): void {
        $this->section_menu[] = [
            'title'           => $this->get_title_to_use($this->top_level_page_id),
            'link_attributes' => [
                'href'         => $this->get_url_to_use($this->top_level_page_id),
                'aria-current' => $this->page_id === $this->top_level_page_id ? 'page' : null
            ],
            'children'        => $this->get_child_pages($this->top_level_page_id)
        ];
    }

    private function get_child_pages($parent_id): array {
        $children = get_pages([
            'parent'      => $parent_id,
            'sort_column' => 'menu_order',
            'sort_order'  => 'ASC',
        ]);

        return array_map(function($child) {
            $redirect = get_post_meta($child->ID, 'redirect_url', true); // corresponds to ACF group "redirect" field "url"
            $newTab = get_post_meta($child->ID, 'redirect_open_in_new_tab', true); // corresponds to ACF group "redirect" field "open_in_new_tab"

            $result = [
                'title'           => $this->get_title_to_use($child->ID),
                'link_attributes' => [
                    'href' => $this->get_url_to_use($child->ID),
                ],
                'children'        => $this->get_child_pages($child->ID)
            ];

            if ($redirect && $newTab) {
                $result['link_attributes']['target'] = '_blank';
            }

            if ($child->ID === $this->page_id) {
                $result['link_attributes']['aria-current'] = 'page';
            }

            return $result;
        }, $children);
    }

    private function get_title_to_use($page_id): string {
        if (class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs_Public')) {
            $breadcrumbTitle = get_post_meta($page_id, 'breadcrumb_title_override', true);
        }

        return !empty($breadcrumbTitle) ? $breadcrumbTitle : get_the_title($page_id);
    }

    private function get_url_to_use($page_id): string {
        $redirect = get_post_meta($page_id, 'redirect_url', true); // corresponds to ACF group "redirect" field "url"

        return !empty($redirect) ? $redirect : get_the_permalink($page_id);
    }
}
