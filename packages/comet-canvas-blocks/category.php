<?php

use Doubleedesign\Comet\Core\{Columns, Column, PageSection, PreprocessedHTML};
use Doubleedesign\CometCanvas\TemplateParts;

get_header();
get_template_part('template-parts/page-header');

$category_id = get_queried_object_id();
$description = get_term_meta($category_id, 'category_description', true);
if (empty($description)) {
    $description = category_description($category_id);
}

if (!empty($description)) {
    $intro = new PreprocessedHTML([], wpautop($description));
}
$posts = TemplateParts::get_posts_loop_cards(['shortName' => 'posts', 'layout' => 'list']);

$wrapperAttrs = [
    'shortName' => 'category',
    'size'      => apply_filters('comet_canvas_default_archive_width', 'contained'),
];

if (isset($intro)) {
    $component = new Columns(
        [
            ...$wrapperAttrs,
            'qty'          => 3,
            'columnLayout' => 'expand-last'
        ],
        [
            new Column(['shortName' => 'intro'], [$intro]),
            new Column(['shortName' => 'posts'], [$posts])

        ]
    );
}
else {
    $component = new PageSection($wrapperAttrs, [$posts]);
}

$component->render();

get_template_part('template-parts/blog-page-blocks');

get_footer();
