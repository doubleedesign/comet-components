<?php

use Doubleedesign\Comet\Core\Container;
use Doubleedesign\CometCanvas\TemplateParts;

$component = new Container([
    'shortName'       => 'categories',
    'size'            => apply_filters('comet_canvas_default_archive_width', 'contained'),
], [TemplateParts::get_all_category_cards()]);
$component->render();
