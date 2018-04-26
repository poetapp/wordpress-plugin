<?php
/**
 * Fired during plugin activation
 *
 * @package    Poet
 * @subpackage Poet/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Poet
 * @subpackage Poet/includes
 */
class Poet_Activator {

	/**
	 * Holds plugin file location
	 *
	 * @var string
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$default = array(
			'api_url' => 'https://api.frost.po.et/works',
			'token'   => '',
			'active'  => 1,
		);
		update_option( 'poet_option', $default );

	}


}
