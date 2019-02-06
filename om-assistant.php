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
	 *
	 * @since 1.0
	 */
	class Om_Assistant {

		/**
		 *
		 * @since 1.0
		 */
		private static $instance;

		/**
		 *
		 * @since 1.0
		 */
		public static function register() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Om_Assistant ) ) {
				self::$instance = new Om_Assistant();
				self::$instance->init();
				self::$instance->define_constants();
				self::$instance->includes();
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function init() {
			add_action( 'enqueue_assets', 'plugin_assets' );
		}

		/**
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
		 *
		 * @param string $name
		 * @param string $value
		 * @since 1.0
		 */
		private function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once HA_PLUGIN_PATH . 'includes/widgets/recent-posts.php';
			require_once HA_PLUGIN_PATH . 'includes/widgets/static-content.php';

			require_once HA_PLUGIN_PATH . 'includes/updater/updater.php';
		}
	}
endif;


/**
 *
 * @since 1.0
 */
function om_assistant() {
	return Om_Assistant::register();
}

/**
 *
 * @since 1.0
 */
function om_assistant_activation_notice() {
	echo '<div class="error"><p>';
	echo esc_html__( 'Om Assistant requires Om WordPress Theme to be installed and activated.', 'om-assistant' );
	echo '</p></div>';
}

/**
 *
 *
 * @since 1.0
 */
function om_assistant_activation_check() {
	$theme = wp_get_theme(); // gets the current theme
	if ( 'Om' == $theme->name || 'Om' == $theme->parent_theme ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			add_action( 'after_setup_theme', 'om_assistant' );
		} else {
			ink_assistant();
		}
	} else {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'om_assistant_activation_notice' );
	}
}

// Theme loads.
om_assistant_activation_check();
