<?php
use Doubleedesign\Comet\Core\{PageHeader, Container, Callout, Paragraph};

get_header();

if(!is_front_page()) {
	get_template_part('template-parts/page-header');

	$callout = new Callout(['colorTheme' => 'error'], [new Paragraph([], 'The page you are looking for does not exist. It may have been removed, had its name changed, or is temporarily unavailable.')]);
	$container = new Container(['size' => 'narrow'], [$callout]);
	$container->render();
}

get_footer();
