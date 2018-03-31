<?php
/*
Plugin Name: Po.et
Plugin URI:  https://github.com/poetapp/wordpress-plugin
Description: Automatically post to Po.et from WordPress using Frost
Version:     1.0.1
Author:      Po.et
Author URI:  https://po.et
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: poet
Domain Path: /languages
*/

defined( 'ABSPATH' ) OR exit;

class Poet {

	/**
	 * Holds plugin file location
	 * @var string
	 */
	private $plugin;

	/**
	 * Initialization method
	 * Used to make an object of the plugin class
	 */
	public static function init() {
		$class = __CLASS__;
		new $class;
	}

	/**
	 * Poet constructor.
	 */
	public function __construct() {
		//Including Consumer class which is needed for API connection
		$dir = dirname( __FILE__ );
		require_once( $dir . '/includes/class-poet-consumer.php' );

		//Setting the plugin file location for later usage
		$this->plugin = plugin_basename( __FILE__ );

		//Setting plugin actions

		register_activation_hook( $this->plugin, array( $this, 'activate' ) );
		register_deactivation_hook( $this->plugin, array( $this, 'deactivate' ) );
		register_uninstall_hook( $this->plugin, array( $this, 'uninstall' ) );
		add_filter( 'plugin_action_links_' . $this->plugin, array( $this, 'add_settings_link' ) );
		add_action( 'poet_set_default_values_on_activation', array( $this, 'set_default_values' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'save_post', array( $this, 'post_article' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
		add_shortcode( 'poet-badge', array( $this, 'poet_badge_handler' ) );
	}


	/**
	 * Activation method
	 * Runs on plugin activation
	 */
	public function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		//Setting default values action to set settings default values on activation/reactivation
		do_action( 'poet_set_default_values_on_activation' );
	}


	/**
	 * Deactivation method
	 * Runs on plugin deactivation
	 */
	public function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		//Unregister plugin settings on deactivation
		unregister_setting( 'poet',
			'poet_option',
			array( $this, 'sanitize' ) );
	}

	/**
	 * Uninstallation method
	 * Runs on plugin deletion
	 */
	public function uninstall() {
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			return;
		}

		//Delete plugin option values from WordPress database
		delete_option( 'poet_option' );
	}

	/**
	 * Setting plugin options default values
	 */
	public function set_default_values() {
		$default = array(
			'api_url'   => 'https://api.frost.po.et/works',
			'token'     => '',
			'active'    => 1
		);
		update_option( 'poet_option', $default );
	}

	/**
	 * Plugin settings page form
	 */
	public function create_options_page() {
		?>
        <div class="wrap">

            <form method="post" action="options.php"><?php
				settings_fields( 'poet' );
				do_settings_sections( $this->plugin );
				submit_button(); ?>
            </form>
        </div>
		<?php
	}

	/**
	 * Registration of settings page in WordPress options menu
	 */
	public function add_options_page() {
		add_options_page( __( 'Po.et' ),
			__( 'Po.et' ),
			'manage_options',
			$this->plugin,
			array( $this, 'create_options_page' ) );
	}

	/**
	 * Adding Settings link in plugins page
	 * The link redirects to plugin settings page
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function add_settings_link( $links ) {
		$url           = menu_page_url( plugin_basename( __FILE__ ), false );
		$settings_link = '<a href="' . $url . '">' . __( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );

		return $links;
	}


	/**
	 * Settings option fields registration
	 */
	public function register_setting() {
		register_setting( 'poet',
			'poet_option',
			array( $this, 'sanitize' ) );

		add_settings_section(
			'poet_setting_section_id', // ID
			__( 'Po.et Settings' ), // Title
			array( $this, 'print_section_info' ), // Callback
			$this->plugin // Page
		);

		add_settings_field(
			'author', // ID
			__( 'Author Name' ), // Title
			array( $this, 'author_callback' ), // Callback
			$this->plugin, // Page
			'poet_setting_section_id' // Section
		);

		add_settings_field(
			'api_url', // ID
			__( 'API URL' ), // Title
			array( $this, 'api_url_callback' ), // Callback
			$this->plugin, // Page
			'poet_setting_section_id' // Section
		);

		add_settings_field(
			'token', // ID
			__( 'API Token' ), // Title
			array( $this, 'token_callback' ), // Callback
			$this->plugin, // Page
			'poet_setting_section_id' // Section
		);

		add_settings_field(
			'active', // ID
			__( 'Post articles automatically on insert or update?' ), // Title
			array( $this, 'active_callback' ), // Callback
			$this->plugin, // Page
			'poet_setting_section_id' // Section
		);
	}

	/**
	 * Prints instruction string in top of settings page
	 */
	public function print_section_info() {
		print __( 'Enter Author Name, API URL, and Token (this will return to default value if the plugin deactivated and reactivated again):' );
	}

	/**
	 * Sanitizes option fields data
	 *
	 * @param $input
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$new_input = array();

		if ( isset( $input['author'] ) ) {
			$new_input['author'] = sanitize_text_field( $input['author'] );
		}

		if ( isset( $input['api_url'] ) ) {
			$new_input['api_url'] = esc_url_raw( $input['api_url'] );
		}
		if ( isset( $input['token'] ) ) {
			$new_input['token'] = sanitize_text_field( $input['token'] );
		}
		if ( isset( $input['active'] ) ) {
			$new_input['active'] = (int) $input['active'];
		}

		return $new_input;
	}

	/**
	 * Returns Author field input
	 */
	public function author_callback() {
		printf(
			'<input type="text" id="author" name="poet_option[author]" value="%s" />',
			isset( get_option( 'poet_option' )['author'] ) ? esc_attr( get_option( 'poet_option' )['author'] ) : ''
		);
	}

	/**
	 * Returns API URL field input
	 */
    public function api_url_callback() {
		printf(
			'<input type="text" id="api_url" name="poet_option[api_url]" value="%s" required />',
			isset( get_option( 'poet_option' )['api_url'] ) ? esc_attr( get_option( 'poet_option' )['api_url'] ) : ''
		);
	}

	/**
	 * Returns Token field input
	 */
    public function token_callback() {
		printf(
			'<input type="text" id="token" name="poet_option[token]" value="%s" required />',
			isset( get_option( 'poet_option' )['token'] ) ? esc_attr( get_option( 'poet_option' )['token'] ) : ''
		);
	}

	/**
	 * Returns activation checkbox input
	 */
    public function active_callback() {
		$checked = isset( get_option( 'poet_option' )['active'] ) ? 1 : 0;
		echo '<input type="checkbox" id="active" name="poet_option[active]" ' . checked( 1, $checked, false ) . ' />';
	}

	/**
	 * Called on WordPress post saving (insertion/modifications)
	 *
	 * @param $post_id
	 */
	public function post_article( $post_id ) {
		$active     = isset( get_option( 'poet_option' )['active'] ) ? 1 : 0;
		$api_url    = ! empty( get_option( 'poet_option' )['api_url'] ) ? 1 : 0;
		$token      = ! empty( get_option( 'poet_option' )['token'] ) ? 1 : 0;
		$post       = get_post( $post_id );

		//Checking if plugin is activated in its settings page and the post status is publish to make sure it is not just a draft
		if ( ! $active || ! $api_url || ! $token || $post->post_status !== 'publish' ) {
			return;
		}

		//Getting API credentials and author name set in plugin settings page
		$author = isset( get_option( 'poet_option' )['author'] ) ? get_option( 'poet_option' )['author'] : '';
		$url    = isset( get_option( 'poet_option' )['api_url'] ) ? get_option( 'poet_option' )['api_url'] : '';
		$token  = isset( get_option( 'poet_option' )['token'] ) ? get_option( 'poet_option' )['token'] : '';

		//Generating Consumer object with credentials sent to its constructor
		$consumer = new Poet_Consumer( $author, $url, $token, $post );

		//Posting the article to the API
		try {
			$response               = $consumer->consume();
			$decoded_response_body  = json_decode( $response['body'] );

			//Adding initial empty meta key for the poet work id
			update_post_meta( $post_id, 'poet_work_id', '' );

			//Checking if the returned response body is a valid JSON string
			if ( json_last_error() !== JSON_ERROR_SYNTAX
				&& is_object( $decoded_response_body )
				&& property_exists( $decoded_response_body, 'workId' ) ) {

				//Creating or updating poet work id meta to the returned work id
				update_post_meta( $post_id, 'poet_work_id', $decoded_response_body->workId );

			}
		} catch ( Exception $exception ) {

		}

	}

	/**
	 * Includes the badge stylesheets in template header
	 */
	public function styles() {
		if ( is_admin() ) {
			return;
		}
		wp_register_style( 'poet-badge-font', 'https://fonts.googleapis.com/css?family=Roboto' );
		wp_enqueue_style( 'poet-badge-font' );
	}

	/**
	 * Handles Verified by Po.et badge shortcode
	 */
	public function poet_badge_handler() {
		$post                   = get_post();
		$quill_image_url        = plugin_dir_url( $this->plugin ) . 'assets/images/quill.svg';
		$post_publication_date  = get_the_modified_time( 'F jS Y, H:i', $post );
		$work_id                = get_post_meta( $post->ID, 'poet_work_id', true );

		ob_start();
		include_once dirname(__FILE__) . '/assets/includes/templates/poet_badge_template.php';
		return ob_get_clean();
	}
}

Poet::init();
