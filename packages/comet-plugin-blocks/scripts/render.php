<?php
/** @var $block array */
/** @var $context array */

use Doubleedesign\Comet\Core\Utils;
use Doubleedesign\Comet\WordPress\BlockRenderer;

$is_editor = isset($is_preview) && $is_preview;
$render_placeholder = BlockRenderer::maybe_render_editor_placeholder($block, $is_editor);
if ($render_placeholder) {
    return;
}

$attributes = Utils::array_pick($block, []);
$component = new BlockTemplate(
    $attributes,
    []
);

if ($context['isNested']) {
    $component->render();
}
else {
    $wrapperAttributes = Utils::array_pick($block, ['size', 'sectionBackground']);
    $wrapper = BlockRenderer::maybe_wrap_component($wrapperAttributes, $component);
    $wrapper->render();
}
