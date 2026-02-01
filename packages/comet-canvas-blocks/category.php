<?php

use Doubleedesign\Comet\Core\{Column, Columns, PreprocessedHTML};
use Doubleedesign\CometCanvas\TemplateParts;

get_header();
get_template_part('template-parts/page-header');

$category_id = get_queried_object_id();
$description = get_term_meta($category_id, 'category_description', true);

if (!empty($description)) {
    $intro = (new Column(
        ['context' => 'category'],
        [new PreprocessedHTML([], wpautop($description))]
    ))->set_bem_modifier('intro');
    $posts = TemplateParts::get_posts_loop_cards(['context' => 'category', 'shortName' => 'posts', 'isNested' => true]);

    $posts->set_is_nested(true);
    $posts = new Column([], [$posts])->set_bem_modifier('posts');
    $innerComponents = isset($intro) ? [$intro, $posts] : [$posts];
    $component = new Columns(['shortName' => 'category'], $innerComponents);
    $component->render();
}
else {
    $component = TemplateParts::get_posts_loop_cards(['context' => 'category', 'shortName' => 'posts', 'layout' => 'grid', 'maxPerRow' => 2]);
    $component->render();
}

get_footer();
