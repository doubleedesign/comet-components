<?php
namespace Doubleedesign\CometCanvas\Classic;
use Doubleedesign\Comet\Core\{Card};
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
}
