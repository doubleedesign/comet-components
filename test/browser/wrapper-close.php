	<?php
	// Non-global assets have to be loaded after component rendering, so this doesn't work in wrapper-open
	use Doubleedesign\Comet\Core\Assets;
	Assets::get_instance()->render_component_stylesheet_html();
	Assets::get_instance()->render_component_script_html();
	?>
	</div>
</body>
</html>
