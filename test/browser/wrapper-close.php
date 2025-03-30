<?php
// If the request has not come from a browser (e.g., it has come from a unit test or CLI command), bail now
if(!isset($_SERVER['HTTP_USER_AGENT'])) return;

// This needs to be output through PHP not as regular HTML
// because otherwise Patchwork outputs things do the page that it means to call if used in a browser environment
echo <<<EOT
		
	</div>
</body>
</html>
EOT;
