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
		include_once dirname( __FILE__ ) . '/partials/class-poet-consumer.php';
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
		wp_enqueue_style( $this->poet, plugin_dir_url( __FILE__ ) . 'css/poet-public.css', array(), $this->version, 'all' );
		wp_register_style( 'poet-badge-font', 'https://fonts.googleapis.com/css?family=Roboto' );
		wp_enqueue_style( 'poet-badge-font' );
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
	 * Handles Verified by Po.et badge shortcode
	 *
	 * @param string $content it is the conten of the html.
	 * @return string html code
	 */
	public function poet_badge_handler( $content ) {
		$post                  = get_post();
		$quill_image_url       = plugin_dir_url( __FILE__ ) . '/images/quill.svg';
		$post_publication_date = get_the_modified_time( 'F jS Y, H:i', $post );
		$work_id               = get_post_meta( $post->ID, 'poet_work_id', true );
		$poet_badge            = '';

		// are we working with a 64 char string.
		if ( strlen( $work_id ) === 64 ) {
			include_once dirname( __FILE__ ) . '/partials/poet-badge-template.php';
			$poet_badge = print_poet_template( $quill_image_url, $work_id, $post_publication_date );
		}

		return $content . $poet_badge;
	}

	/**
	 * Called on WordPress post saving (insertion/modifications)
	 *
	 * @param string $post_id ID post from WP.
	 */
	public function post_article( $post_id ) {

		$active  = isset( get_option( 'poet_option' )['active'] ) ? 1 : 0;
		$api_url = ! empty( get_option( 'poet_option' )['api_url'] ) ? 1 : 0;
		$token   = ! empty( get_option( 'poet_option' )['token'] ) ? 1 : 0;
		$post    = get_post( $post_id );
		// Checking if plugin is activated in its settings page and the post status is publish to make sure it is not just a draft.
		if ( 'publish' !== $post->post_status || ! $active || ! $api_url || ! $token ) {
			return;
		}
		// Getting API credentials and author name set in plugin settings page.
		$author = isset( get_option( 'poet_option' )['author'] ) ? get_option( 'poet_option' )['author'] : '';
		$url    = isset( get_option( 'poet_option' )['api_url'] ) ? get_option( 'poet_option' )['api_url'] : '';
		$token  = isset( get_option( 'poet_option' )['token'] ) ? get_option( 'poet_option' )['token'] : '';

		// Generating Consumer object with credentials sent to its constructor.
		$consumer = new Poet_Consumer( $author, $url, $token, $post );

		// Posting the article to the API.
		try {
			$response              = $consumer->consume();
			$decoded_response_body = json_decode( $response['body'] );

			// Adding initial empty meta key for the poet work id.
			update_post_meta( $post_id, 'poet_work_id', '' );

			// Checking if the returned response body is a valid JSON string.
			if ( json_last_error() !== JSON_ERROR_SYNTAX
				&& is_object( $decoded_response_body )
				&& property_exists( $decoded_response_body, 'workId' ) ) {

				// Creating or updating poet work id meta to the returned work id.
				update_post_meta( $post_id, 'poet_work_id', $decoded_response_body->{'workId'} );

			}
		} catch ( Exception $e ) {
			update_post_meta( $post_id, 'poet_work_id', 'fail in post call' );
		}

	}

}
