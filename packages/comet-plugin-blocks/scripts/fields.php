<?php

use Doubleedesign\Comet\WordPress\BlockFieldHandler;

if(!function_exists('acf_add_local_field_group')) {
	return;
}

acf_add_local_field_group(array(
	'key'        => 'layout_block-template',
	'title'      => 'Block Template',
	'fields'     => array(
		array(
			'key'               => 'field__',
			'label'             => '',
			'name'              => '',
			'type'              => '',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'comet/block-template',
			),
		),
	),
));
