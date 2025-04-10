<?php
$referrer = $_SERVER['HTTP_REFERER'] ?? null;
if($referrer && str_ends_with($referrer, '/test/browser/')) {
	echo 'No test component for that yet';
}
else {
	// Redirect to the documentation
	header('location: /docs/index.html');
	exit;
}
