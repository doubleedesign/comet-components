<?php
use Doubleedesign\Comet\Core\{Container, Card};

get_header();
do_action('comet_canvas_blog_top_content');

if (is_home()) {
    // Get all categories and show cards for them
}
else {
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

    $component = new Container(
        ['size' => 'default'],
        $cards
    );
    $component->render();
}

get_footer();
