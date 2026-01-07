<?php
acf_add_local_field_group(array(
    'key'                   => 'layout_upcoming-events',
    'title'                 => 'Upcoming Events block',
    'fields'                => array(
        array(
            'key'               => 'field__events__heading',
            'label'             => 'Heading',
            'name'              => 'heading',
            'type'              => 'text',
            'default_value'     => 'Upcoming Events',
            'maxlength'         => 40
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
    'active'                => true,
    'show_in_rest'          => 0,
));
