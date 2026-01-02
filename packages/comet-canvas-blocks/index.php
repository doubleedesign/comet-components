<?php
use Doubleedesign\Comet\Core\PageHeader;

get_header();

if (!is_front_page()) {
	get_template_part('template-parts/page-header');
}

if (is_page()) {
    the_content();
}
if (is_archive() && have_posts()) {
    while (have_posts()) {
        //		the_post();
        //		echo get_the_title();
    }
}

get_footer();
