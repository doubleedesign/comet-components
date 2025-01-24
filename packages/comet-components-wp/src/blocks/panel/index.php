<?php
$allowed_blocks = ['comet/panel-title', 'core/heading', 'core/paragraph', 'core/image', 'core/heading', 'core/list'];
$template = [
	['comet/panel-title', ['placeholder' => 'Add panel title here...']],
	['core/paragraph', ['placeholder' => 'Add panel content here...']]
];
?>

<div class="panel">
	<InnerBlocks
		allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
		template="<?php echo esc_attr(wp_json_encode($template)); ?>"
	/>
</div>
