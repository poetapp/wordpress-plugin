<?php
/**
 * Fired during plugin deactivation
 *
 * @package    Poet
 * @subpackage Poet/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    Poet
 * @subpackage Poet/includes
 */
class Poet_Deactivator {

	/**
	 * Deactivative plugin.
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		// Unregister plugin settings on deactivation.
		unregister_setting( 'poet',
			'poet_option',
		array( self, 'sanitize' ) );
	}

}
