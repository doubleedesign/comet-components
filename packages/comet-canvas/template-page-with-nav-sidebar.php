<?php
/**
 * Template Name: With section navigation sidebar
 */
use Doubleedesign\Comet\Core\{PageHeader, Container, Columns, Column, SectionMenu};
use Doubleedesign\Comet\WordPress\PreprocessedHTML;
use Doubleedesign\CometCanvas\SectionMenuBuilder;

global $post;

if(class_exists("Doubleedesign\Breadcrumbs\Breadcrumbs")) {
	$breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
	$pageHeader = new PageHeader(['size' => 'narrow'], get_the_title(), $breadcrumbs);
}
else {
	$pageHeader = new PageHeader(['size' => 'narrow'], get_the_title());
}

// Process block strings so custom Comet rendering is applied, rather than rendering raw WP blocks
$content = do_blocks(get_the_content());
$sidebarMenu = (new SectionMenuBuilder($post->ID))->get_menu();
$sidebar = new SectionMenu(['context' => 'sidebar'], $sidebarMenu);

$transformedContent = new Container(
	['classes' => ['page-with-sidebar'], 'backgroundColor' => 'white'],
	// 'classes' => ['columns'] and 'classes' => ['columns__column']
	// is a workaround to keep the default contextual classes as well as the custom ones
	[new Columns(['context' => 'page-with-sidebar', 'classes' => ['columns']], [
		new Column(
			['tagName' => 'main', 'context' => 'page-with-sidebar__columns', 'classes' => ['columns__column']],
			[new PreprocessedHTML([], $content)]
		),
		new Column(
			['tagName' => 'aside', 'context' => 'page-with-sidebar__columns', 'classes' => ['columns__column']],
			[$sidebar]
		)
	])]
);

get_header();
$pageHeader->render();
$transformedContent->render();
get_footer();
