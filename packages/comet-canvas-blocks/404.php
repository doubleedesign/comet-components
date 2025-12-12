<?php
use Doubleedesign\Comet\Core\{PageHeader, Container, Callout, Paragraph};

get_header();

if(!is_front_page()) {
	if(class_exists("Doubleedesign\Breadcrumbs\Breadcrumbs")) {
		$breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
		$pageHeader = new PageHeader(['size' => 'narrow'], 'Page not found', $breadcrumbs);
		$pageHeader->render();
	}
	else {
		$pageHeader = new PageHeader(['size' => 'narrow'], 'Page not found');
		$pageHeader->render();
	}

	$callout = new Callout(['colorTheme' => 'error'], [new Paragraph([], 'The page you are looking for does not exist. It may have been removed, had its name changed, or is temporarily unavailable.')]);

	$container = new Container(['size' => 'narrow'], [$callout]);
	$container->render();
}

get_footer();
