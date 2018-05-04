<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Poet
 * @subpackage Poet/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Poet
 * @subpackage Poet/admin
 */
class Poet_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $poet;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Plugin Base Name
	 *
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name       The name of this plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->poet    = $plugin_name;
		$this->version = $version;
		$this->plugin  = plugin_basename( __FILE__ );
	}

	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Register the JavaScript for the admin area.
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
	}

	/**
	 * Adding Settings link in plugins page
	 * The link redirects to plugin settings page
	 *
	 * @param string $links links for settings.
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
		register_setting( 'poet', 'poet_option', array( $this, 'sanitize' ) );
		add_settings_section( 'poet_setting_section_id', __( 'Po.et Settings' ), array( $this, 'print_section_info' ), $this->plugin );
		add_settings_field(
			'author', // ID.
			__( 'Author Name' ), // Title.
			array( $this, 'author_callback' ), // Callback.
			$this->plugin, // Page.
			'poet_setting_section_id' // Section.
		);
		add_settings_field(
			'api_url', // ID.
			__( 'API URL' ), // Title.
			array( $this, 'api_url_callback' ), // Callback.
			$this->plugin, // Page.
			'poet_setting_section_id' // Section.
		);
		add_settings_field(
			'token', // ID.
			__( 'API Token' ), // Title.
			array( $this, 'token_callback' ), // Callback.
			$this->plugin, // Page.
			'poet_setting_section_id' // Section.
		);
		add_settings_field(
			'active', // ID.
			__( 'Post articles automatically on insert or update?' ), // Title.
			array( $this, 'active_callback' ), // Callback.
			$this->plugin, // Page.
			'poet_setting_section_id' // Section.
		);
	}

	/**
	 * Prints instruction string in top of settings page
	 */
	public function print_section_info() {
		echo esc_html( 'Enter Author Name, API URL, and Token (this will return to default value if the plugin deactivated and reactivated again):' );
	}

	/**
	 * Sanitizes option fields data
	 *
	 * @param string $input imput to sinitize.
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
	 * Registration of settings page in WordPress options menu
	 */
	public function add_options_page() {
		add_options_page( __( 'Po.et' ),
			__( 'Po.et' ),
			'manage_options',
			$this->plugin,
			array( $this, 'create_options_page' )
		);
	}

	/**
	 * Plugin settings page form
	 */
	public function create_options_page() {
		?>
		<div class="wrap">

			<form method="post" action="options.php">
				<?php
				settings_fields( 'poet' );
				do_settings_sections( $this->plugin );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
