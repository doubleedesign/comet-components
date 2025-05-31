<?php
if (!str_contains($_SERVER['HTTP_HOST'], 'storybook')) {
    // Redirect to the documentation
    header('location: /docs/index.html');
    exit;
}
