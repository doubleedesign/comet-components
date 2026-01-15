<?php
namespace Doubleedesign\Comet\WordPress\Calendar;

class Fields {

    public function __construct() {
        add_action('acf/include_fields', [$this, 'register_options_fields']);
        add_action('acf/include_fields', [$this, 'register_archive_fields']);
        add_action('acf/include_fields', [$this, 'register_post_fields']);
    }

    public function register_options_fields(): void {
        acf_add_local_field_group(array(
            "key"   => "group__comet-calendar--settings",
            "title" => "Calendar Settings",
            "fields"=> [
                [
                    "key"          => "field__comet-calendar__enable-detail-pages",
                    "label"        => "Enable event detail pages",
                    "name"         => "enable_event_detail_pages",
                    "type"         => "select",
                    'choices'      => [
                        'always'      => 'Always',
                        'upcoming'    => 'Only for upcoming events',
                        'past_year'   => 'Only for events in the past year',
                        'current_year'=> 'Only for events in the current year',
                        'never'       => 'Never'
                    ],
                    "instructions" => "If disabled, event detail URLs will redirect to the Events page, where a simple list of events is shown. Items in event lists will not link to the detail page. This setting is site-wide, so it affects the Upcoming Events block as well as the Events/Calendar page.",
                ]
            ],
            "location"=> [
                [
                    [
                        "param"   => "options_page",
                        "operator"=> "==",
                        "value"   => "calendar-settings"
                    ]
                ]
            ],
            "menu_order"           => 0,
            "position"             => "normal",
            "style"                => "default",
            "label_placement"      => "top",
            "instruction_placement"=> "label",
            "active"               => true,
            "show_in_rest"         => false,
            "modified"             => 1745712330
        ));
    }

    public function register_archive_fields(): void {
        acf_add_local_field_group(
            array(
                "key"   => "group__comet-calendar--archive-settings",
                "title" => "Calendar Page",
                "fields"=> [
                    [
                        "key"          => "field__comet-calendar__archive-title",
                        "label"        => "Page title",
                        "name"         => "events_page_title",
                        "type"         => "text",
                        "instructions" => "Note: A lowercase version of this is also used for the event URLs.",
                        "default_value"=> "Calendar",
                        "maxlength"    => 50
                    ],
                    [
                        "key"          => "field__comet-calendar__archive-past-events",
                        "label"        => "Show past events",
                        "name"         => "show_past_events",
                        "instructions" => "Whether to show the \"Past events\" section on the page. Note: This will not stop direct links to past events from working if \"Enable event detail pages\" is on. To completely hide an event from public view without deleting it form the admin, make it a draft, private, or manually add a redirect in its settings.",
                        "type"         => "select",
                        'choices'      => [
                            'always'      => 'Always',
                            'past_year'   => 'Only events in the past year',
                            'current_year'=> 'Only events in the current year',
                            'never'       => 'Never'
                        ],
                    ],
                    [
                        "key"          => "field__comet-calendar__archive-events-per-row",
                        "label"        => "Events per row",
                        "name"         => "events_per_row",
                        "type"         => "number",
                        "default_value"=> 3,
                        "min"          => 1,
                        "max"          => 4
                    ]
                ],
                "location"=> [
                    [
                        [
                            "param"   => "options_page",
                            "operator"=> "==",
                            "value"   => "calendar-settings"
                        ]
                    ]
                ],
                "menu_order"           => 1,
                "position"             => "normal",
                "style"                => "default",
                "label_placement"      => "top",
                "instruction_placement"=> "label",
                "active"               => true,
                "show_in_rest"         => false,
                "modified"             => 1745726306
            )
        );
    }

    public function register_post_fields(): void {
        acf_add_local_field_group(array(

            "key"   => "group_event_details",
            "title" => "Event details",
            "fields"=> [
                [
                    "key"    => "field__event__type",
                    "label"  => "Type",
                    "name"   => "type",
                    "type"   => "select",
                    "choices"=> [
                        "single"        => "Single date",
                        "range"         => "Date range",
                        "multi"         => "Multiple dates",
                    ],
                    "default_value"=> false,
                    "return_format"=> "value"
                ],
                [
                    "key"              => "field__event__date--single",
                    "label"            => "Single date",
                    "name"             => "single",
                    "type"             => "group",
                    "conditional_logic"=> [
                        [
                            [
                                "field"   => "field__event__type",
                                "operator"=> "==",
                                "value"   => "single"
                            ]
                        ]
                    ],
                    "layout"    => "table",
                    "sub_fields"=> [
                        [
                            "key"            => "field__event__date--single__date",
                            "label"          => "Date",
                            "name"           => "date",
                            "type"           => "date_picker",
                            "display_format" => "d/m/Y",
                            "return_format"  => "F j, Y",
                            "first_day"      => 1
                        ],
                        [
                            "key"           => "field__event__date--single__start-time",
                            "label"         => "Start time",
                            "name"          => "start_time",
                            "type"          => "time_picker",
                            "display_format"=> "g:i a",
                            "return_format" => "H:i"
                        ],
                        [
                            "key"           => "field__event__date--single__end-time",
                            "label"         => "End time",
                            "name"          => "end_time",
                            "type"          => "time_picker",
                            "display_format"=> "g:i a",
                            "return_format" => "H:i"
                        ]
                    ]
                ],
                [
                    "key"              => "field__event__date--range",
                    "label"            => "Date range",
                    "name"             => "range",
                    "type"             => "group",
                    "conditional_logic"=> [
                        [
                            [
                                "field"   => "field__event__type",
                                "operator"=> "==",
                                "value"   => "range"
                            ]
                        ]
                    ],
                    "layout"    => "table",
                    "sub_fields"=> [
                        [
                            "key"            => "field__event__date--range__start-date",
                            "label"          => "Start date",
                            "name"           => "start_date",
                            "type"           => "date_picker",
                            "display_format" => "d/m/Y",
                            "return_format"  => "F j, Y",
                            "first_day"      => 1
                        ],
                        [
                            "key"              => "field__event__date--range__end-date",
                            "label"            => "End date",
                            "name"             => "end_date",
                            "type"             => "date_picker",
                            "display_format"   => "d/m/Y",
                            "return_format"    => "F j, Y",
                            "first_day"        => 1
                        ]
                    ]
                ],
                [
                    "key"              => "field__event__date--multiple",
                    "label"            => "Multiple dates",
                    "name"             => "multi",
                    "type"             => "repeater",
                    "conditional_logic"=> [
                        [
                            [
                                "field"   => "field__event__type",
                                "operator"=> "==",
                                "value"   => "multi"
                            ]
                        ]
                    ],
                    "layout"       => "table",
                    "button_label" => "Add date",
                    "rows_per_page"=> 20,
                    "sub_fields"   => [
                        [
                            "key"            => "field__event__date--multiple__date--date",
                            "label"          => "Date",
                            "name"           => "date",
                            "type"           => "date_picker",
                            "display_format" => "d/m/Y",
                            "return_format"  => "F j, Y",
                            "first_day"      => 1,
                            "parent_repeater"=> "field__event__date--multiple",
                            "wrapper"        => [
                                "width"=> "33"
                            ]
                        ],
                        [
                            "key"            => "field__event__date--multiple__date--start-time",
                            "label"          => "Start time",
                            "name"           => "start_time",
                            "type"           => "time_picker",
                            "display_format" => "g:i a",
                            "return_format"  => "H:i",
                            "parent_repeater"=> "field__event__date--multiple",
                            "wrapper"        => [
                                "width"=> "33"
                            ]
                        ],
                        [
                            "key"            => "field__event__date--multiple__date--end-time",
                            "label"          => "End time",
                            "name"           => "end_time",
                            "type"           => "time_picker",
                            "display_format" => "g:i a",
                            "return_format"  => "H:i",
                            "parent_repeater"=> "field__event__date--multiple",
                            "wrapper"        => [
                                "width"=> "33"
                            ]
                        ]
                    ]
                ],
                [
                    "key"  => "field__event__location",
                    "label"=> "Location",
                    "name" => "location",
                    "type" => "text"
                ],
                [
                    "key"          => "field__event__link",
                    "label"        => "External link",
                    "name"         => "external_link",
                    "type"         => "link",
                    "instructions" => "Link to ticketing or other external website if applicable",
                    "return_format"=> "array"
                ],
                [
                    "key"          => "field__event__additional-links",
                    "label"        => "Additional links",
                    "name"         => "additional_links",
                    "instructions" => "Note: By default, these links only show on single event detail pages, if enabled. Themes may override this behaviour with custom templates.",
                    "type"         => "repeater",
                    "layout"       => "table",
                    "button_label" => "Add link",
                    "sub_fields"   => [
                        [
                            "key"            => "field__event__additional-links__link",
                            "label"          => "Link",
                            "name"           => "link",
                            "type"           => "link",
                            "return_format"  => "array",
                            "parent_repeater"=> "field__event__additional-links"
                        ]
                    ]
                ],
            ],
            "location"=> [
                [
                    [
                        "param"   => "post_type",
                        "operator"=> "==",
                        "value"   => "event"
                    ]
                ]
            ],
            "menu_order"           => 0,
            "position"             => "acf_after_title",
            "style"                => "default",
            "label_placement"      => "top",
            "instruction_placement"=> "label",
            "active"               => true,
            "show_in_rest"         => true,
            "modified"             => 1745552785

        ));
    }
}
