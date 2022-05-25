<?php
/**
 * Plugin Name: Redvolver Bubble
 * Plugin URI: http://redvolver.it
 * Description: Bubbles Rocks!!!
 * Author: Redvolver
 * Author URI: http://redvolver.it
 * Version: 1.0.0
 * Text Domain: redvolver
 * Domain Path: languages
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'RV_Bubble' ) ) :

final class RV_Bubble {
	/** Singleton *************************************************************/

	/**
	 * @var RV_Bubble The one true RV_Bubble
	 */
	private static $instance;

	/**
	 * Main RV_Bubble Instance
	 *
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof RV_Bubble ) ) {
			self::$instance = new RV_Bubble;
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

			self::$instance->includes();

		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'redvolver' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'redvolver' ), '1.0' );
	}

	/**
	 * Setup plugin constants
	 *
	 */
	private function setup_constants() {

		// Plugin version
		if ( ! defined( 'RV_BUBBLE_VERSION' ) ) {
			define( 'RV_BUBBLE_VERSION', '1.0.0' );
		}

		// Plugin Folder Path
		if ( ! defined( 'RV_BUBBLE_PLUGIN_DIR' ) ) {
			define( 'RV_BUBBLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL
		if ( ! defined( 'RV_BUBBLE_PLUGIN_URL' ) ) {
			define( 'RV_BUBBLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File
		if ( ! defined( 'RV_BUBBLE_PLUGIN_FILE' ) ) {
			define( 'RV_BUBBLE_PLUGIN_FILE', __FILE__ );
		}
	}

	/**
	 * Include required files
	 *
	 */
	private function includes() {
		require_once RV_BUBBLE_PLUGIN_DIR . 'lib/carbon-fields/carbon-fields-plugin.php';
		if ( is_admin() ) {
			require_once RV_BUBBLE_PLUGIN_DIR . 'includes/admin/class-redvolver-admin.php';
			require_once RV_BUBBLE_PLUGIN_DIR . 'includes/admin/class-redvolver-metabox.php';
		}
		require_once RV_BUBBLE_PLUGIN_DIR . 'includes/class-redvolver-frontend.php';
		require_once RV_BUBBLE_PLUGIN_DIR . 'redvolver-function.php';
	}

	/**
	 * Loads the plugin language files
	 *
	 */
	public function load_textdomain() {

		load_plugin_textdomain( 'redvolver', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

}

endif; // End if class_exists check


/**
 * The main function responsible for returning RV_Bubble
 */
function RVB() {
	return RV_Bubble::instance();
}

// Get Bubble Running
RVB();
