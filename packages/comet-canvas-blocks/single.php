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
        'context'         => 'post-content',
        'src'             => $image_url,
        'alt'             => $image_alt,
        'caption'         => $image_caption,
        'aspectRatio'     => 'cinemascope',
        'scale'           => 'cover',
        'isNested'        => true,
        'classes'         => apply_filters('comet_canvas_blog_post_featured_image_classes', []),
        'styleName'       => apply_filters('comet_canvas_blog_post_featured_image_style', ''),
    ]);
}

$content = new Copy([
    'context'           => 'post-content',
    'shortName'         => 'body',
    'isNested'          => true,
    'colorTheme'        => 'primary',
], [new PreprocessedHTML([], wpautop(get_the_content()))]);

$include_author_card = apply_filters('comet_canvas_blog_post_include_author_card', false);
$include_post_nav = apply_filters('comet_canvas_blog_post_include_post_nav', true);

$footer = new Group([
    'tagName'       => 'footer',
    'context'       => 'post-content',
    'shortName'     => 'footer',
], [
    ...($include_author_card ? [TemplateParts::get_author_card()] : []),
    ...($include_post_nav ? [TemplateParts::get_post_nav()] : []),
]);

$component = new Container([
    'tagName'         => 'article',
    'shortName'       => 'post-content',
    'isNested' 	      => false,
    // Associate this <article> with its headline contained in the page header component
    'aria-labelledby'   => 'page-header--post-' . get_the_id(),
], [
    ...(isset($image) ? [$image] : []),
    $content,
    $footer
]);

$component->render();

get_footer();
