<?php
namespace Doubleedesign\CometCanvas\Classic;
use Doubleedesign\Comet\Core\Config;
use JetBrains\PhpStorm\NoReturn;

class AdminUI {

    public function __construct() {
        add_action('admin_head', [$this, 'add_colour_css_variables_to_admin'], 1);
        add_action('admin_init', [$this, 'disable_customiser']);
        add_action('login_enqueue_scripts', [$this, 'login_logo']);
        add_action('admin_menu', [$this, 'remove_metaboxes']);
        add_action('add_meta_boxes', [$this, 'remove_metaboxes'], 99);
        add_action('init', [$this, 'register_theme_support'], 5);
        add_action('admin_notices', [$this, 'required_plugin_notification'], 10);
    }

    /**
     * Add CSS variables for the colour palette to the admin area
     * so they can be used in ACF fields etc
     */
    public function add_colour_css_variables_to_admin(): void {
        $colours = Config::getInstance()->get_theme_colours();

        if (!empty($colours)) {
            echo '<style>:root {';
            foreach ($colours as $value => $label) {
                echo '--color-' . strtolower($label) . ': #' . $value . ';';
            }
            echo '} </style>';
        }
    }

    /**
     * Disable the Customiser in favour of having most things together in an ACF options page
     * and because it will probably be deprecated as the block editor takes over
     * Largely lifted from the Customizer Disabler plugin, except I haven't bothered to remove capabilities
     * https://wordpress.org/plugins/customizer-disabler/
     *
     * @return void
     */
    public function disable_customiser(): void {
        remove_action('plugins_loaded', '_wp_customize_include');
        remove_action('admin_enqueue_scripts', '_wp_customize_loader_settings', 11);
        add_action('load-customize.php', [$this, 'override_load_customizer_action']);
        add_action('admin_init', [$this, 'remove_customiser_from_menu'], 99);
    }

    #[NoReturn] public function override_load_customizer_action(): void {
        wp_die(__('The Customiser is currently disabled.', 'customizer-disabler'));
    }

    public function remove_customiser_from_menu(): void {
        $customize_url = add_query_arg('return', urlencode(remove_query_arg(wp_removable_query_args(), wp_unslash($_SERVER['REQUEST_URI']))), 'customize.php');
        remove_submenu_page('themes.php', $customize_url);
    }

    /**
     * Customise login screen logo
     */
    public function login_logo(): void {
        $custom_logo_id = get_option('options_logo');

        // If we're using ClassicPress and its option for a logo is set, don't override it, but do tweak some CSS
        if (function_exists('classicpress_version') && get_option('login_custom_image_state') == 1) {
            ?>
				<style>
					.login .custom-login-image-container img {
						max-width: 200px !important;
						max-height: none !important;
					}
				</style>
			<?php
        }
        else if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            ?>
			<style>
				body.login.wp-core-ui {
				}

				#login h1 a {
					width: 75%;
					min-height: 80px;
					background-image: url('<?php echo $logo[0]; ?>') !important;
					padding-bottom: 0 !important;
					background-size: contain !important;
				}

				#login #nav a, #login #backtoblog a {
				}
			</style>
		<?php }
        }

    /**
     * Remove unwanted metaboxes
     */
    public function remove_metaboxes(): void {
        remove_meta_box('nf_admin_metaboxes_appendaform', array('page', 'post'), 'side');
    }

    /**
     * Register theme support for the relevant backend features
     * Note: I'm leaning towards using an ACF options page for most things
     * rather than the Customizer (which does support adding a logo for example),
     * simply to keep things in one place
     */
    public function register_theme_support(): void {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails', array('post', 'page', 'event', 'person'));
        add_post_type_support('page', 'excerpt');
    }

    /**
     * Add admin notices for required and strongly recommended plugins
     *
     * @return void
     */
    public function required_plugin_notification(): void {
        $status = SiteHealth::required_plugins_status();

        if (count($status['errors']) > 0) {
            echo '<div class="notice notice-error">';
            echo '<p>The following plugins are required to be installed and activated for full functionality of your Double-E Design theme. Without them, some features may be missing or not work as expected.</p>';
            echo '<ul>';
            foreach ($status['errors'] as $error) {
                echo '<li>' . $error . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }

        if (count($status['warnings']) > 0) {
            echo '<div class="notice notice-warning">';
            echo '<p>The following plugins are recommended for full functionality of your Double-E Design theme.</p>';
            echo '<ul>';
            foreach ($status['warnings'] as $warning) {
                echo '<li>' . $warning . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }

}
