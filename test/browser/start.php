<?php
// Get the current directory and replace backslashes with forward slashes
$currentDir = str_replace('\\', '/', __DIR__);

// Create/update php.ini file with paths to the files to prepend and append to each component template file to make them complete webpages
// (e.g., add doctype, html, head, and body tags; these are PHP not HTML so we can also include PHP in the header to call in the functions that our components need)
$phpIniContent = "
auto_prepend_file = $currentDir/wrapper-open.php
auto_append_file = $currentDir/wrapper-close.php
";

// Path for the custom php.ini file
$phpIniPath = $currentDir . '/php.ini';

// Write the content to the file
file_put_contents($phpIniPath, $phpIniContent);

// Port to use for the web server that will serve the components
$port = 6001;

// Kill any hanging previous PHP server processes (otherwise php.ini caching issues can happen)
exec("netstat -ano | findstr :$port", $output);
if(!empty($output)) {
	foreach($output as $line) {
		if(preg_match('/\s+(\d+)\s*$/', $line, $matches)) {
			$pid = $matches[1];
			exec("taskkill /PID $pid /F");
		}
	}
}

// Clear Blade template cache
array_map('unlink', glob(dirname(__DIR__, 2) . '/cache/blade/*'));

// Add an environment variable to set the environment to development
putenv("APP_ENV=development");
// Add an environment variable with the location of the current PHP executable
putenv("PHP_EXECUTABLE=" . PHP_BINARY);
// Specify to use the custom php.ini file as an additional configuration, so the current PHP instance's is used as well
putenv("PHP_INI_SCAN_DIR=$currentDir");
// Start the server
exec("php -S localhost:$port -t $currentDir");
