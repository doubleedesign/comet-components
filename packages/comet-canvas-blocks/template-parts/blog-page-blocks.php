<?php
$blog_page = get_option('page_for_posts');
if ($blog_page) {
	$blocks = parse_blocks(get_post_field('post_content', $blog_page));
	foreach ($blocks as $block) {
		echo render_block($block);
	}
}
