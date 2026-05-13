<?php

use Doubleedesign\Comet\Core\Container;
use Doubleedesign\CometCanvas\TemplateParts;

$component = new Container([
    'shortName' => 'categories',
    'size'      => apply_filters('comet_canvas_default_archive_width', 'contained'),
], [TemplateParts::get_posts_loop_cards()]);
$component->render();
