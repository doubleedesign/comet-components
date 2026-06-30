<?php

use Doubleedesign\Comet\Core\{Config, PageHeader};

$attributes = Config::getInstance()->get_component_defaults('page-header');
$queried_object = get_queried_object();
$display_title = get_post_meta(get_queried_object_id(), 'display_heading', true);
$title = !empty($display_title) ? $display_title : get_the_title();

if (is_home() && !is_front_page()) {
    $title = get_the_title(get_option('page_for_posts', true));
	// If we are on page 2+, optionally append extra text as defined in the theme (e.g., "Archive")
	if(is_paged()) {
		$append = apply_filters('comet_canvas_append_to_blog_archive_pages', '');
		$title .= " " . $append;
	}
}
if (is_archive()) {
    $title = $queried_object->label ?? get_the_archive_title();
}
if (is_404()) {
	$title = __('Page Not Found', 'comet-canvas-blocks');
}

if (is_single()) {
    $attributes['id'] = 'page-header--post-' . get_the_ID();
}

$title = apply_filters('comet_canvas_page_header_title', $title, 'page-header');

if (class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs')) {
    $breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
    $pageHeader = new PageHeader($attributes, $title, $breadcrumbs);
}
else {
    $pageHeader = new PageHeader($attributes, $title);
}

$pageHeader->render();
