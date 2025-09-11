<?php
use Doubleedesign\CometCanvas\Classic\SharedContent;

get_header();
the_content();
SharedContent::render_appended_content();
get_footer();
