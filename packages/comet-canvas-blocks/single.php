<?php

use Doubleedesign\Comet\Core\{Container, ContentImageBasic, Copy, Group, PreprocessedHTML};
use Doubleedesign\CometCanvas\TemplateParts;

get_header();
get_template_part('template-parts/page-header');

$image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
if ($image_url) {
    $image_alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
    $image_caption = get_the_post_thumbnail_caption();
    $image = new ContentImageBasic([
        'context'     => 'post-content',
        'src'         => $image_url,
        'alt'         => $image_alt,
        'caption'     => $image_caption,
        'aspectRatio' => 'cinemascope',
        'scale'       => 'cover',
        'isNested'    => true,
        'classes'     => ['breakout']
    ]);
}

$content = new Copy([
    'context'           => 'post-content',
    'shortName'         => 'body',
    'isNested'          => true,
    'colorTheme'        => 'primary',
], [new PreprocessedHTML([], wpautop(get_the_content()))]);

$footer = new Group([
    'tagName'       => 'footer',
    'context'       => 'post-content',
    'shortName'     => 'footer',
], [
    TemplateParts::get_author_card(),
    TemplateParts::get_post_nav() ?? []
]);

$component = new Container([
    'tagName'         => 'article',
    'shortName'       => 'post-content',
    'withWrapper'     => true,
    // Associate this <article> with its headline contained in the page header component
    'aria-labelledby'   => 'page-header--post-' . get_the_id(),
], [
    ...(isset($image) ? [$image] : []),
    $content,
    $footer
]);

$component->render();

get_footer();
