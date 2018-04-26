<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package Poet
 * @subpackage Poet/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Poet
 * @subpackage Poet/public
 */
class Poet_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->poet    = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
	 * Register the JavaScript for the public-facing side of the site.
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
	 *
	 * @param string $content it is the conten of the html.
	 * @return string html code
	 */
	public function poet_badge_handler( $content ) {
		$post                  = get_post();
		$quill_image_url       = get_site_url() . '/wp-content/plugins/poet/public/images/quill.svg';
		$post_publication_date = get_the_modified_time( 'F jS Y, H:i', $post );
		$work_id               = get_post_meta( $post->ID, 'poet_work_id', true );
		$poet_badge            = '';
		if ( is_single() ) {
			ob_start();
			include_once dirname( __FILE__ ) . '/partials/poet-badge-template.php';
			$poet_badge = ob_get_clean();
		}
		return $content . $poet_badge;
	}

}
