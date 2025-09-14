<?php
use Doubleedesign\Comet\Core\{Card, Container, ContentImageBasic, CopyBlock};
use Doubleedesign\Comet\WordPress\Classic\PreprocessedHTML;

get_header();

do_action('comet_canvas_blog_top_content');

// The filter that makes the flexible content render for the_content doesn't apply to get_the_content,
// and that doesn't have any filters, so output buffering is the workaround for now.
ob_start();
the_content();
$content = ob_get_clean();

$image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
if ($image_url) {
    $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    $image_caption = get_the_post_thumbnail_caption();
    $image = new ContentImageBasic([
        'src'         => $image_url,
        'alt'         => $image_alt,
        'caption'     => $image_caption,
        'aspectRatio' => '16:9',
        'scale'       => 'cover',
        'isNested'    => true,
    ]);
}

$author_id = get_the_author_id();
$user_query = new WP_User_Query(['include' => [$author_id]]);
$author_data = $user_query->get_results()[0]->data;
$author_card = new Card([
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

$content_component = new CopyBlock([
    'tagName'         => 'article',
    // Associate this <article> with its headline contained in the page header component
    'aria-labelledby' => 'page-header--post-' . get_the_id(),
    'isNested'        => false,
    'withWrapper'     => false,
    'context'         => 'post-content',
    'size'            => 'default',
], [
    ...(isset($image) ? [$image] : []),
    new PreprocessedHTML([], $content)]
);

$footer = new Container([
    'tagName'     => 'footer',
    'context'     => 'post-footer',
    'isNested'    => false,
    'withWrapper' => false,
    'size'        => 'narrow'
], [
    $author_card
]);

$content_component->render();
$footer->render();

get_footer();
