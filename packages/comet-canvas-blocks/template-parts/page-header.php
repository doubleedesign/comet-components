<?php

use Doubleedesign\Comet\Core\{Config, PageHeader};

$attributes = Config::getInstance()->get_component_defaults('page-header');
$queried_object = get_queried_object();
$display_title = get_post_meta(get_queried_object_id(), 'display_heading', true);
$title = !empty($display_title) ? $display_title : get_the_title();

if (is_home() && !is_front_page()) {
    $title = get_the_title(get_option('page_for_posts', true));
}
if (is_archive()) {
    $title = $queried_object->label ?? get_the_archive_title();
}

if (is_single()) {
    $attributes['id'] = 'page-header--post-' . get_the_ID();
}

$title = apply_filters('comet_canvas_page_header_title', $title);

if (class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs')) {
    $breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_breadcrumbs();
    $pageHeader = new PageHeader($attributes, $title, $breadcrumbs);
}
else {
    $pageHeader = new PageHeader($attributes, $title);
}

$pageHeader->render();
