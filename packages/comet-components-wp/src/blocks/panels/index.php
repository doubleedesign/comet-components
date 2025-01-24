<?php
$allowed_blocks = ['comet/panel'];
$template = [['comet/panel'], ['comet/panel'], ['comet/panel']];

?>
<div>
	<InnerBlocks
		allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
		template="<?php echo esc_attr(wp_json_encode($template)); ?>"
	/>
</div>
