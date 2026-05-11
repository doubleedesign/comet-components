<?php
get_header();

if (!is_front_page()) {
    get_template_part('template-parts/page-header');
}

if (is_page() || is_front_page() || is_single()) {
    the_content();
}
// Blog index (this MUST be in an else-if after the check for is_front_page, or it will return true for the homepage when we don't want it to)
else if (is_home() && apply_filters('comet_canvas_show_category_cards_on_blog_index', true)) {
    // Get all categories and show cards for them
    get_template_part('template-parts/category-cards');
    get_template_part('template-parts/blog-page-blocks');
}
else {
    // Probably on a taxonomy archive or similar at this point, where we want to show the standard posts list
    get_template_part('template-parts/posts-loop');
    get_template_part('template-parts/blog-page-blocks');
}
get_footer();
