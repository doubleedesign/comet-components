<?php

use Doubleedesign\Comet\Core\PageSection;
use Doubleedesign\CometCanvas\TemplateParts;

$component = new PageSection([
    'shortName'       => 'blog-list',
	'classes'         => ['categories'],
    'size'            => apply_filters('comet_canvas_default_archive_width', 'contained'),
], [TemplateParts::get_all_category_cards()]);
$component->render();
