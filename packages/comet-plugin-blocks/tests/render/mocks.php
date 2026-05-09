<?php
if (!function_exists('get_field')) {
    function get_field($field) {
        if ($field === 'heading') {
            return 'The cushions are the essence of the chair!';
        }

        return null;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($filter_name, $value) {
        return $value;
    }
}
