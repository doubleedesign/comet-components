<?php
/** @var $args array */
use Doubleedesign\Comet\Core\IconLinks;

$socials = function_exists('get_field') ? (get_field('social_media_links', 'options') ?? []) : [];
if (!is_array($socials)) {
    $socials = [];
}

$component = new IconLinks([
    'aria-label'  => 'Social media links',
    'orientation' => 'horizontal',
    ...$args
], $socials);

$component->render();
