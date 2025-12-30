<?php

namespace Doubleedesign\CometCanvas\Classic;

use Doubleedesign\Comet\Core\Config;

class TinyMCEConfig {

    public function __construct() {
        add_filter('tiny_mce_before_init', [$this, 'add_theme_colours'], 10, 1);

        // Note: The Comet for ACF plugin also adds its own customisations to the buttons available in ACF WYSIWYG fields.
    }

    /**
     * Add predefined colours to the colour picker
     * Note: This will probably be deprecated in favour of whole-component colour theming, as is done in the block editor version
     *
     * @wp-hook
     *
     * @param  $settings
     *
     * @return array
     */
    public function add_theme_colours($settings): array {
        $colours = Config::getInstance()->get_theme_colours();

        if (!empty($colours)) {
            $map = array();
            foreach ($colours as $value => $label) {
                $map[] = '"' . $value . '","' . $label . '"';
            }

            $settings['textcolor_map'] = '[' . implode(',', $map) . ']';
        }

        return $settings;
    }
}
