<?php
use Doubleedesign\Comet\Core\{Container, Columns, Column, Card, CopyBlock};
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
            'colorTheme'        => 'primary',
            'orientation'       => 'horizontal'
        ]);
    }
}

$intro = new Column(['width' => '30%'], [new CopyBlock(['isNested' => true, 'context' => 'copy-block'], [new PreprocessedHTML([], get_category(get_queried_object_id())->description)])]);
$list = new Column(['context' => 'card-list', 'width' => '70%'], $cards);

$component = new Container(
    ['size' => 'default'],
    [new Columns([], [$intro, $list])]
);
$component->render();

get_footer();
