<?php
namespace Doubleedesign\CometCanvas\Classic;
use Doubleedesign\Comet\Core\{Card, PostNav};
use WP_User_Query;

/**
 * Class TemplateParts
 *
 * Returns common template parts as Comet Components using utility functions,
 * rather than traditional template part HTML that would require output buffering and wrapping in order to put inside another Comet Component in a template.
 * Where these should be made available to child themes, they should have an accompanying global function in this theme's functions.php
 * so that child  themes don't need to have the parent theme as a Composer dependency or load its autoloader or the class file directly to use these.
 *
 * @package Doubleedesign\CometCanvas\Classic
 * @version 1.0.0
 */
class TemplateParts {

    public static function get_author_card(): Card {
        $author_id = get_the_author_meta('ID');
        $user_query = new WP_User_Query(['include' => [$author_id]]);
        $author_data = $user_query->get_results()[0]->data;

        return new Card([
            'context'    => 'author-bio',
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
        $entityName = get_post_type() == 'post' ? 'Article' : get_post_type_object(get_post_type())->labels->singular_name;

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

        return new PostNav([
            'links'      => array_filter([$prev, $next]),
            'entityName' => $entityName,
            'colorTheme' => 'secondary'
        ]);
    }
}
