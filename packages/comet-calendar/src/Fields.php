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
            "key"   => "group_6806310ca3e03",
            "title" => "Calendar Settings",
            "fields"=> [
                [
                    "key"          => "field_6806310d41619",
                    "label"        => "Enable event detail pages",
                    "name"         => "enable_event_detail_pages",
                    "type"         => "true_false",
                    "instructions" => "If disabled, event detail URLs will redirect to the Events page, where a simple list of events is shown. Items in event lists will not link to the detail page. This setting is site-wide, so it affects the Upcoming Events block as well as the Events/Calendar page.",
                    "default_value"=> true,
                    "ui_on_text"   => "",
                    "ui_off_text"  => "",
                    "ui"           => true
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
                "key"   => "group_680d74d84020b",
                "title" => "Calendar Page",
                "fields"=> [
                    [
                        "key"          => "field_680d74d8d3a0c",
                        "label"        => "Page title",
                        "name"         => "events_page_title",
                        "type"         => "text",
                        "instructions" => "Note: A lowercase version of this is also used for the event URLs.",
                        "default_value"=> "Calendar",
                        "maxlength"    => 50
                    ],
                    [
                        "key"          => "field_680d7510d43da",
                        "label"        => "Show past events",
                        "name"         => "show_past_events",
                        "type"         => "true_false",
                        "instructions" => "Whether to show the \"Past events\" section on the page. Note: This will not stop direct links to past events from working if \"Enable event detail pages\" is on. To completely hide an event from public view without deleting it form the admin, make it a draft, private, or manually add a redirect in its settings.",
                        "default_value"=> true,
                        "ui_on_text"   => "",
                        "ui_off_text"  => "",
                        "ui"           => true
                    ],
                    [
                        "key"          => "field_680da53bfa93b",
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
                        "multi"         => "Multiple dates, same time",
                        "multi_extended"=> "Multiple dates, different times"
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
                            "key"           => "field__event__date--single__date",
                            "label"         => "Date",
                            "name"          => "date",
                            "type"          => "date_picker",
                            "display_format"=> "F j, Y",
                            "return_format" => "F j, Y",
                            "first_day"     => 1
                        ],
                        [
                            "key"           => "field__event__date--single__start-time",
                            "label"         => "Start time",
                            "name"          => "start_time",
                            "type"          => "time_picker",
                            "display_format"=> "g=>i a",
                            "return_format" => "g=>i a"
                        ],
                        [
                            "key"           => "field__event__date--single__end-time",
                            "label"         => "End time",
                            "name"          => "end_time",
                            "type"          => "time_picker",
                            "display_format"=> "g=>i a",
                            "return_format" => "g=>i a"
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
                            "key"           => "field__event__date--range__start-date",
                            "label"         => "Start date",
                            "name"          => "start_date",
                            "type"          => "date_picker",
                            "display_format"=> "F j, Y",
                            "return_format" => "F j, Y",
                            "first_day"     => 1
                        ],
                        [
                            "key"              => "field__event__date--range__end-date",
                            "label"            => "End date",
                            "name"             => "end_date",
                            "type"             => "date_picker",
                            "display_format"   => "F j, Y",
                            "return_format"    => "F j, Y",
                            "first_day"        => 1
                        ]
                    ]
                ],
                [
                    "key"              => "field__event__date--multiple",
                    "label"            => "Multiple dates",
                    "name"             => "multi",
                    "type"             => "group",
                    "conditional_logic"=> [
                        [
                            [
                                "field"   => "field__event__type",
                                "operator"=> "==",
                                "value"   => "multi"
                            ]
                        ]
                    ],
                    "layout"    => "table",
                    "sub_fields"=> [
                        [
                            "key"          => "field__event__date--multiple__dates",
                            "label"        => "Dates",
                            "name"         => "dates",
                            "type"         => "repeater",
                            "layout"       => "table",
                            "pagination"   => 0,
                            "min"          => 0,
                            "max"          => 0,
                            "collapsed"    => "",
                            "button_label" => "Add date",
                            "rows_per_page"=> 20,
                            "sub_fields"   => [
                                [
                                    "key"            => "field__event__date--multiple__date",
                                    "label"          => "Date",
                                    "name"           => "date",
                                    "type"           => "date_picker",
                                    "display_format" => "F j, Y",
                                    "return_format"  => "F j, Y",
                                    "first_day"      => 1,
                                    "parent_repeater"=> "field__event__date--multiple__dates"
                                ],
                            ]
                        ],
                        [
                            "key"            => "field__event__date--multiple__start-time",
                            "label"          => "Start time",
                            "name"           => "start_time",
                            "type"           => "time_picker",
                            "display_format" => "g=>i a",
                            "return_format"  => "g=>i a",
                        ],
                        [
                            "key"            => "field__event__date--multiple__end-time",
                            "label"          => "End time",
                            "name"           => "end_time",
                            "type"           => "time_picker",
                            "display_format" => "g=>i a",
                            "return_format"  => "g=>i a",
                        ]
                    ]
                ],
                [
                    "key"              => "field__event__date--multiple-extended",
                    "label"            => "Multiple dates and times",
                    "name"             => "multi_extended",
                    "type"             => "repeater",
                    "conditional_logic"=> [
                        [
                            [
                                "field"   => "field__event__type",
                                "operator"=> "==",
                                "value"   => "multi_extended"
                            ]
                        ]
                    ],
                    "layout"       => "table",
                    "pagination"   => 0,
                    "min"          => 0,
                    "max"          => 0,
                    "collapsed"    => "",
                    "button_label" => "Add date",
                    "rows_per_page"=> 20,
                    "sub_fields"   => [
                        [
                            "key"            => "field__event__date--multiple_extended__date",
                            "label"          => "Date",
                            "name"           => "date",
                            "type"           => "date_picker",
                            "display_format" => "F j, Y",
                            "return_format"  => "F j, Y",
                            "first_day"      => 1,
                            "parent_repeater"=> "field__event__date--multiple_extended",
                            "wrapper"        => [
                                "width"=> "33"
                            ]
                        ],
                        [
                            "key"            => "field__event__date--multiple_extended__date__start-time",
                            "label"          => "Start time",
                            "name"           => "start_time",
                            "type"           => "time_picker",
                            "display_format" => "g=>i a",
                            "return_format"  => "g=>i a",
                            "parent_repeater"=> "field__event__date--multiple__dates",
                            "wrapper"        => [
                                "width"=> "33"
                            ]
                        ],
                        [
                            "key"            => "field__event__date--multiple_extended__date__end-time",
                            "label"          => "End time",
                            "name"           => "end_time",
                            "type"           => "time_picker",
                            "display_format" => "g=>i a",
                            "return_format"  => "g=>i a",
                            "parent_repeater"=> "field__event__date--multiple__dates",
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
                ]
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
