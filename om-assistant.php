<?php
/**
 * Plugin Name: Om Assistant
 * Plugin URI: https://github.com/Codestag/om-assistant
 * Description: A plugin to assist Om theme in adding widgets.
 * Author: Codestag
 * Author URI: https://codestag.com
 * Version: 1.0
 * Text Domain: om-assistant
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Om
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Om_Assistant' ) ) :
	/**
	 * Om Assistant.
	 *
	 * @since 1.0
	 */
	class Om_Assistant {

		/**
		 * Class instance.
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Register method to create a new instance.
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Om_Assistant ) ) {
				self::$instance = new Om_Assistant();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 * Defines plugin constants.
		 *
		 * @since 1.0
		 */
		public function define_constants() {
			$this->define( 'OA_VERSION', '1.0' );
			$this->define( 'OA_DEBUG', true );
			$this->define( 'OA_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'OA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Method to define a constant.
		 *
		 * @param string $name name of constant.
		 * @param string $value value of the constant.
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Includes plugin files.
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once OA_PLUGIN_PATH . 'includes/widgets/class-stag-widget.php';
			require_once OA_PLUGIN_PATH . 'includes/widgets/recent-posts.php';
			require_once OA_PLUGIN_PATH . 'includes/widgets/static-content.php';
		}
	}
endif;


/**
 * Invokes Om_Assistant Class.
 *
 * @since 1.0
 */
function om_assistant() {
	return Om_Assistant::register();
}

/**
 * Activation notice.
 *
 * @since 1.0
 */
function om_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Om Assistant requires Om WordPress Theme to be installed and activated.', 'om-assistant' );
	echo '</p></div>';
}

/**
 * Assistant activation check.
 *
 * @since 1.0
 */
function om_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Om' === $theme->name || 'Om' === $theme->parent_theme ) {
		add_action( 'after_setup_theme', 'om_assistant' );
	} else {
		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'om_assistant_activation_notice' );
	}
}

// Plugin loads.
om_assistant_activation_check();
