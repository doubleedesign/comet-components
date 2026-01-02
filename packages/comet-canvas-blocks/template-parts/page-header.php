<?php
use Doubleedesign\Comet\Core\Config;
use Doubleedesign\Comet\Core\PageHeader;

$attributes = Config::getInstance()->get_component_defaults('page-header');

if (class_exists('Doubleedesign\Breadcrumbs\Breadcrumbs')) {
	$breadcrumbs = Doubleedesign\Breadcrumbs\Breadcrumbs::$instance->get_raw_breadcrumbs();
	$pageHeader = new PageHeader($attributes, get_the_title(), $breadcrumbs);
}
else {
	$pageHeader = new PageHeader($attributes, get_the_title());
}

$pageHeader->render();
