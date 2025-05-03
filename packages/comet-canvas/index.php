<?php
use Doubleedesign\Comet\Core\PageHeader;

get_header();

if(!is_front_page()) {
	if(class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs')) {
		$breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
		$pageHeader = new PageHeader(['size' => 'default'], get_the_title(), $breadcrumbs);
	}
	else {
		$pageHeader = new PageHeader(['size' => 'default'], get_the_title());
	}

	$pageHeader->render();
}

if(is_page()) {
	the_content();
}
if(is_archive() && have_posts()) {
	while(have_posts()) {
//		the_post();
//		echo get_the_title();
	}
}

get_footer();
