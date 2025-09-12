<?php
use Doubleedesign\Comet\Core\Container;
use Doubleedesign\Comet\WordPress\Classic\PreprocessedHTML;

get_header();
do_action('comet_canvas_blog_top_content');

// The filter that makes the flexible content render for the_content doesn't apply to get_the_content,
// and that doesn't have any filters, so output buffering is the workaround for now.
ob_start();
the_content();
$content = ob_get_clean();

$container = new Container(['withWrapper' => false], [new PreprocessedHTML([], $content)]);
$container->render();

get_footer();
