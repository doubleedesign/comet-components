<?php
acf_add_local_field_group(array(
	'key'                   => 'group_680e125da97bf',
	'title'                 => 'Upcoming Events block',
	'fields'                => array(
		array(
			'key'               => 'field_680e128c4074c',
			'label'             => 'Heading',
			'name'              => 'heading',
			'aria-label'        => '',
			'type'              => 'text',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '',
			'maxlength'         => '',
			'allow_in_bindings' => 0,
			'placeholder'       => '',
			'prepend'           => '',
			'append'            => '',
		),
		array(
			'key'               => 'field_680e16554e0e3',
			'label'             => 'Items to show',
			'name'              => 'items_to_show',
			'aria-label'        => '',
			'type'              => 'number',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '50',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => 3,
			'min'               => '',
			'max'               => '',
			'allow_in_bindings' => 0,
			'placeholder'       => '',
			'step'              => '',
			'prepend'           => '',
			'append'            => '',
		),
		array(
			'key'               => 'field_680e12604074b',
			'label'             => 'Max items per row',
			'name'              => 'max_items_per_row',
			'aria-label'        => '',
			'type'              => 'number',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => array(
				'width' => '50',
				'class' => '',
				'id'    => '',
			),
			'default_value'     => '',
			'min'               => '',
			'max'               => '',
			'allow_in_bindings' => 0,
			'placeholder'       => '',
			'step'              => '',
			'prepend'           => '',
			'append'            => '',
		),
	),
	'location'              => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'comet/upcoming-events',
			),
		),
	),
	'menu_order'            => 0,
	'position'              => 'normal',
	'style'                 => 'default',
	'label_placement'       => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'        => '',
	'active'                => true,
	'description'           => '',
	'show_in_rest'          => 0,
));
