<?php
$is_editor = isset($is_preview) && isset($block) && $is_preview;
// Array of block data should be passed in from get_template_part as $args['block'] or from the editor as $block
if (!$is_editor && !isset($args) || ($is_editor && !isset($block))) {
	return;
}

$default_blocks = [['core/details', []]];
$allowed_blocks = ['core/details'];

if ($is_editor) { ?>
	<InnerBlocks template="<?php echo esc_attr(wp_json_encode($default_blocks)); ?>"
	             allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"></InnerBlocks>
<?php }
if (!$is_editor && isset($args['block'])) {
	if ($args['block']['innerBlocks']) {
		echo '<pre>';
		echo print_r($args['block'], true);
		echo '</pre>';
	}
}
