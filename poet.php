<?php
/**
Plugin Name: Po.et
Plugin URI:  https://github.com/poetapp/wordpress-plugin
Description: Automatically post to Po.et from WordPress using Frost
Version:     1.0.2-dev
Author:      Po.et
Author URI:  https://po.et
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: poet
Domain Path: /languages
 *
 * @package /Poet
 */

defined( 'ABSPATH' ) || exit;

define( 'POET_VERSION', '1.0.2-dev' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_poet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-poet-activator.php';
	Poet_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_poet() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-poet-deactivator.php';
	Poet_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_poet' );
register_deactivation_hook( __FILE__, 'deactivate_poet' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-poet.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_poet() {
	$plugin = new Poet();
	$plugin->run();
}
run_poet();
