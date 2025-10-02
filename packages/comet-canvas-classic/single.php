<?php
use Doubleedesign\Comet\Core\{Container, ContentImageBasic};
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
        'aspectRatio' => 'cinemascope',
        'scale'       => 'cover',
        'isNested'    => true,
        'classes'     => ['breakout']
    ]);
}

$content_component = new Container([
    'tagName'         => 'article',
    // Associate this <article> with its headline contained in the page header component
    'aria-labelledby'   => 'page-header--post-' . get_the_id(),
    'isNested'          => false,
    'shortName'         => 'post-content',
], [
    ...(isset($image) ? [$image] : []),
    new PreprocessedHTML([], $content),
]
);

$footer = new Container([
    'tagName'       => 'footer',
    'shortName'     => 'post-footer',
    'isNested'      => false,
    'size'          => 'default'
], [
    comet_get_author_card(),
    comet_get_post_nav() ?? []
]);

$content_component->render();
$footer->render();

get_footer();
