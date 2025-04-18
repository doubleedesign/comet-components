<?php
// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail early
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

// Skip all this if this is not Comet Components
// Useful for local development where php.ini applies to multiple sites
if(!in_array($_SERVER['HTTP_HOST'], ['comet-components.test', 'cometcomponents.io'])) return;

// Component-level assets have to be loaded after components have been instantiated, so this doesn't work in wrapper-open
use Doubleedesign\Comet\Core\Assets;

Assets::get_instance()->render_component_stylesheet_html();
Assets::get_instance()->render_component_script_html();
?>
</div>
</body>
</html>
