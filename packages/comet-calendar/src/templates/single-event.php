<?php
/**
 * I usually believe display is the responsibility of the theme, but because the plugin is used to provide event-related blocks
 * (which is logical to avoid over-coupling this plugin with the Comet Canvas theme),
 * it also makes sense to include default archive and single event templates here too.
 * They can still be overridden by the theme as per the standard WordPress template hierarchy.
 */
get_header();
get_template_part('template-parts/page-header');

get_footer();
