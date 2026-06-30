<?php

use Doubleedesign\Comet\Core\PageSection;
use Doubleedesign\CometCanvas\TemplateParts;

// Get the page we are on in the loop, if applicable
$page = get_query_var('paged') ? get_query_var('paged') : 1;

$component = new PageSection([
	'tagName' => 'section',
    'shortName' => 'posts',
    'size'      => apply_filters('comet_canvas_default_archive_width', 'contained'),
	'data-page' => $page
], [
	TemplateParts::get_posts_loop_cards(),
	TemplateParts::get_pagination('posts')
]);
$component->render();
