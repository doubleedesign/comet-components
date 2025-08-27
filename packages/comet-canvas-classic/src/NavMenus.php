<?php
namespace Doubleedesign\CometCanvas\Classic;

class NavMenus {

    public function __construct() {
        add_action('init', [$this, 'register_menus'], 20);
        add_filter('nav_menu_link_attributes', [$this, 'menu_link_classes'], 10, 4);
        add_filter('nav_menu_submenu_css_class', [$this, 'menu_submenu_classes'], 10, 2);
    }

    /**
     * Register menus in the back-end
     *
     * @return void
     */
    public function register_menus(): void {
        register_nav_menus(array(
            'primary' => 'Primary menu',
            'footer'  => 'Footer menu'
        ));
    }

    /**
     * Add classes to menu <a> tags
     *
     * @param  $atts
     * @param  $item
     * @param  $args
     * @param  $depth
     *
     * @return array
     */
    public function menu_link_classes($atts, $item, $args, $depth): array {
        if ($args->theme_location == 'header' && $depth == 0 && in_array('menu-item-has-children', $item->classes)) {
            $atts['class'] = 'menu-dropdown-link';
        }

        return $atts;
    }

    /**
     * Add classes to sub-menu <ul>
     *
     * @param  $classes
     * @param  $args
     *
     * @return array
     */
    public function menu_submenu_classes($classes, $args): array {
        if ($args->theme_location == 'header') {
            $classes[] = 'dropdown-menu';
        }

        return $classes;
    }

    /**
     * Get nav menu items by location
     *
     * @param  $location  string menu location name
     * @param  array  $args  args to pass to WordPress function wp_get_nav_menu_items
     *
     * @return array
     */
    public static function get_nav_menu_items_by_location(string $location, array $args = []): array {
        $locations = get_nav_menu_locations();
        $page_for_posts = get_option('page_for_posts');

        if (isset($locations[$location])) {
            $object = wp_get_nav_menu_object($locations[$location]);
            $items = wp_get_nav_menu_items($object->name, $args);
            $current = get_queried_object();
            $default_category_id = get_option('default_category');

            // $current = the currently viewed page/post/archive/other object
            // $item = the current item in the menu loop we're assessing for relationship to $current.
            // $item is a nav_menu_item object, not a post/taxonomy object, so we need to use other fields in it than ID to ascertain the ID we actually want
            foreach ($items as $item) {
                if (isset($current->post_type) && $current->post_type == 'page') {
                    if ($current->ID == $item->object_id) {
                        $item->is_current = true;
                    }
                    if ($current->post_parent == $item->object_id) {
                        $item->is_current_parent = true;
                    }
                }
                else if (isset($current->taxonomy) && $current->taxonomy == 'category') {
                    if (($item->object_id == $page_for_posts) || ($item->object_id == $default_category_id)) {
                        $item->is_current = true;
                    }
                }
                else if (isset($current->post_type) && $current->post_type == 'post') {
                    if (($item->object_id == $page_for_posts) || ($item->object_id == $default_category_id)) {
                        $item->is_current_parent = true;
                    }
                }
                else if (isset($current->post_type) && $item->type == 'post_type_archive') {
                    if ($current->post_type == $item->object) {
                        $item->is_current_parent = true;
                    }
                }
                else if ($item->type == 'post_type_archive' && isset($current->name) && $current->name == $item->object) {
                    $item->is_current = true;
                }

                if ($item->url) {
                    if (parse_url($item->url)['host'] !== parse_url(get_bloginfo('url'))['host']) {
                        $item->classes[] = 'external';
                    }
                }
            }

            return $items;
        }

        return [];
    }

    public static function get_simplified_nav_menu_items_by_location(string $location) {
        $items = self::get_nav_menu_items_by_location($location);
        $result = array_reduce($items, function($acc, $item) {
            // menu_item_parent is the corresponding nav_menu_item ID, not the post/taxonomy object ID
            if ($item->menu_item_parent > 0) {
                $acc[$item->menu_item_parent]['children'][] = [
                    // use the nav_menu_item ID not the object ID because it will be unique,
                    // whereas the same object can be linked to multiple times in one menu which would cause duplicates
                    'id'              => $item->ID,
                    'title'           => $item->title,
                    'classes'         => array_filter($item->classes, fn($class) => !empty($class)),
                    'isCurrentParent' => $item->is_current_parent,
                    'link_attributes' => [
                        'href'         => $item->url,
                        'target'       => $item->target,
                        'title'        => $item->attr_title,
                        'rel'          => $item->xfn,
                        'classes'      => [],
                        'aria-current' => $item->is_current ? 'page' : null
                    ]
                ];

                return $acc;
            }

            $acc[$item->ID] = [
                // use the nav_menu_item ID not the object ID because it will be unique,
                // whereas the same object can be linked to multiple times in one menu which would cause duplicates
                'id'              => $item->ID,
                'title'           => $item->title,
                'classes'         => array_filter($item->classes, fn($class) => !empty($class)),
                'isCurrentParent' => $item->is_current_parent,
                'link_attributes' => [
                    'href'         => $item->url,
                    'target'       => $item->target,
                    'title'        => $item->attr_title,
                    'rel'          => $item->xfn,
                    'classes'      => [],
                    'aria-current' => $item->is_current ? 'page' : null
                ],
                'children'        => []
            ];

            return $acc;
        }, []);

        // Reset array indexes before returning
        return array_values($result);
    }

}
