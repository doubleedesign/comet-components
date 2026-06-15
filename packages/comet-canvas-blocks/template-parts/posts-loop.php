<?php

use Doubleedesign\Comet\Core\PageSection;
use Doubleedesign\CometCanvas\TemplateParts;

$component = new PageSection([
	'tagName' => 'section',
    'shortName' => 'posts',
    'size'      => apply_filters('comet_canvas_default_archive_width', 'contained'),
], [TemplateParts::get_posts_loop_cards()]);
$component->render();
