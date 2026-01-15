<?php
namespace Doubleedesign\CometCanvas;
use Doubleedesign\Comet\Core\{Card, CardList, Config, PostNav};
use WP_User_Query;

/**
 * Class TemplateParts
 *
 * Contains utility functions to get info to put into templates,
 * and functions to return common template parts as Comet Components using utility functions.
 * The latter is instead of traditional template part HTML that would require output buffering and wrapping in order to put inside another Comet Component in a template.
 * Where these should be made available to child themes, they should have an accompanying global function in this theme's functions.php
 * so that child  themes don't need to have the parent theme as a Composer dependency or load its autoloader or the class file directly to use these.
 *
 * @package Doubleedesign\CometCanvas\Classic
 * @version 1.0.0
 */
class TemplateParts {

    public static function get_contact_details_fields(): array {
        $expected = array_reduce(['address', 'suburb', 'state', 'postcode', 'phone', 'email'], function($carry, $field) {
            $value = get_option("options_contact_details_$field");
            if ($value) {
                $carry[$field] = $value;
            }

            return $carry;
        }, []);

        return apply_filters('comet_canvas_classic_contact_details_fields', $expected);
    }

    public static function get_author_card(): Card {
        $queried_object = get_queried_object();
        $author_id = $queried_object->post_author;
        $user_query = new WP_User_Query(['include' => [$author_id]]);
        $author_data = $user_query->get_results()[0]->data;

        return new Card([
            'classes'    => ['author-bio'],
            'heading'    => "<span>About the author</span>" . $author_data->display_name,
            'bodyText'   => get_user_meta($author_id, 'description', true),
            'colorTheme' => 'primary',
            'link'       => [
                'href'      => $author_data->user_url ?: get_author_posts_url($author_id),
                'content'   => 'More about ' . get_user_meta($author_id, 'first_name', true) ?? $author_data->display_name ?? 'the author',
                'isOutline' => true
            ]
        ]);
    }

    public static function get_post_nav(): PostNav {
        $queried_object = get_queried_object();
        $entityName = $queried_object->post_type == 'post' ? 'Article' : get_post_type_object(get_post_type())->labels->singular_name;

        $post = get_post();
        setup_postdata($post);
        $prev_post = get_previous_post();
        $next_post = get_next_post();

        $prev_link = $prev_post ? get_permalink($prev_post->ID) : null;
        $next_link = $next_post ? get_permalink($next_post->ID) : null;

        $prev = null;
        $next = null;

        if ($prev_link) {
            $prev = [
                'href'    => $prev_link,
                'content' => get_the_title($prev_post->ID)
            ];
        }

        if ($next_link) {
            $next = [
                'href'    => $next_link,
                'content' => get_the_title($next_post->ID)
            ];
        }

        wp_reset_postdata();

        return new PostNav([
            'links'      => array_filter([$prev, $next]),
            'entityName' => $entityName,
            'colorTheme' => 'secondary'
        ]);
    }

    /**
     * Get a CardList of Cards representing the current posts loop.
     *
     * @param  array  $attributes  - additional attributes to pass to the CardList.
     *                             Intended for attributes like context, shortName, and isNested.
     *
     * @return CardList
     */
    public static function get_posts_loop_cards(array $attributes = []): CardList {
        $cards = [];
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                $post_id = get_the_id();
                $title = get_the_title();
                $excerpt = get_the_excerpt();
                $image = get_the_post_thumbnail_url($post_id, 'large') ?: '';
                $alt = get_post_meta(get_post_thumbnail_id($post_id), '_wp_attachment_image_alt', true);
                $link = get_permalink($post_id);

                $cards[] = new Card([
                    'tagName'     => 'article',
                    'heading'     => $title,
                    'bodyText'    => $excerpt,
                    'image'       => [
                        'src' => $image,
                        'alt' => $alt,
                    ],
                    'link'        => [
                        'href'      => $link,
                        'content'   => 'Read more',
                        'isOutline' => true
                    ],
                    'colorTheme'  => 'primary',
                    'orientation' => 'horizontal'
                ]);
            }
        }

        $cardLayout = Config::getInstance()->get_component_defaults('card-list')['layout'] ?? 'list';
        $cardLayout = apply_filters('comet_canvas_posts_loop_card_layout', $cardLayout);
        $cardsPerRow = Config::getInstance()->get_component_defaults('card-list')['maxPerRow'] ?? 3;
        $cardsPerRow = apply_filters('comet_canvas_posts_loop_cards_per_row', $cardsPerRow);

        return new CardList(
            [
                'size'      => 'default',
                'maxPerRow' => $cardsPerRow,
                'layout'    => $cardLayout,
                ...$attributes
            ],
            $cards
        );
    }

    /**
     * Get a CardList of Cards representing all post categories.
     *
     * @return CardList
     */
    public static function get_all_category_cards(): CardList {
        $categories = get_categories();
        $cards = [];
        foreach ($categories as $category) {
            $category_link = get_category_link($category->term_id);
            $description = get_term_meta($category->term_id, 'category_description', true);
            $image_id = get_term_meta($category->term_id, 'category_image', true);
            $alt = get_post_meta(get_post_thumbnail_id($image_id), '_wp_attachment_image_alt', true);

            $cards[] = new Card([
                'heading'     => $category->name,
                'bodyText'    => wpautop($description),
                'image'       => [
                    'src' => $image_id ? wp_get_attachment_url($image_id) : '',
                    'alt' => $alt,
                ],
                'link'        => [
                    'href'      => esc_url($category_link),
                    'content'   => 'View posts',
                    'isOutline' => true
                ],
                'colorTheme'  => 'primary',
                'orientation' => 'horizontal'
            ]);
        }

        $cardLayout = Config::getInstance()->get_component_defaults('card-list')['layout'] ?? 'list';
        $cardLayout = apply_filters('comet_canvas_post_category_list_card_layout', $cardLayout);
        $cardsPerRow = Config::getInstance()->get_component_defaults('card-list')['maxPerRow'] ?? 3;
        $cardsPerRow = apply_filters('comet_canvas_post_category_list_cards_per_row', $cardsPerRow);

        return new CardList(
            [
                'size'      => 'default',
                'maxPerRow' => $cardsPerRow,
                'layout'    => $cardLayout,
            ],
            $cards
        );
    }
}
