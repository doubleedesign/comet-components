<?php
use Doubleedesign\Comet\Core\{Columns, Column, CardList, Card, Copy};
use Doubleedesign\Comet\WordPress\Classic\PreprocessedHTML;

get_header();
do_action('comet_canvas_blog_top_content');

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
            'tagName'           => 'article',
            'heading'           => $title,
            'bodyText'          => $excerpt,
            'image'             => [
                'src'   => $image,
                'alt'   => $alt,
            ],
            'link'              => [
                'href'      => $link,
                'content'   => 'Read more',
                'isOutline' => true
            ],
            'orientation'       => 'horizontal',
            // no need to set colour theme unless it differs from that of the CardList
        ]);
    }
}

$list = (new Column(
    [],
    [new CardList(['colorTheme' => 'primary', 'gridLayout' => false], $cards)]
))->set_bem_modifier('posts');

$category_id = get_queried_object_id();
$description = get_term_meta($category_id, 'category_description', true);
if (!empty($description)) {
    $intro = (new Column(
        [],
        [new Copy(['isNested' => true], [new PreprocessedHTML([], wpautop($description))])]
    ))->set_bem_modifier('intro');
}

$innerComponents = isset($intro) ? [$intro, $list] : [$list];
$component = new Columns(['shortName' => 'category'], $innerComponents);
$component->render();

get_footer();
