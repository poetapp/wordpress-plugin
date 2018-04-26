<?php
/**
 * Class with all functions of Poet Code
 *
 * @package    Poet
 * @subpackage Poet/admin/partials
 */

defined( 'ABSPATH' ) || exit;
/**
 * Class Consumer
 * Holds the responsibility of contacting the API and exchanging data with it
 *
 * @param string $author author of the Post.
 */
class Poet_Consumer {
	/**
	 * Author name
	 *
	 * @var string
	 */
	private $author;
	/**
	 * URL of post
	 *
	 * @var string
	 */
	private $url;
	/**
	 * Security token Confirmation
	 *
	 * @var string
	 */
	private $token;
	/**
	 * Post Name
	 *
	 * @var string
	 */
	private $post;

	/**
	 * Consumer constructor.
	 *
	 * @param string $author name of the author.
	 * @param string $url url of the post.
	 * @param string $token validation token.
	 * @param string $post post ID.
	 */
	public function __construct( $author, $url, $token, $post ) {
		$this->author = $author;
		$this->url    = $url;
		$this->token  = $token;
		$this->post   = $post;
	}

	/**
	 * Get Author Name method
	 *
	 * Returns author name set in settings page or the name of the user who published the post
	 *
	 * @return string
	 */
	private function get_author_name() {
		$author = $this->author;

		if ( empty( $author ) ) {
			$user   = get_user_by( 'ID', $this->post->post_author );
			$author = $user->display_name;
			if ( ! empty( $user->first_name ) ) {
				$author = $user->first_name;
				if ( ! empty( $user->last_name ) ) {
					$author .= ' ' . $user->last_name;
				}
			} elseif ( ! empty( $user->last_name ) ) {
				$author = $user->last_name;
			}
		}

		return $author;
	}

	/**
	 * Consume method
	 *
	 * The main method used to send articles to Po.et API
	 *
	 * @return array|WP_Error
	 */
	public function consume() {
		$tags_array = wp_get_post_tags( $this->post->ID, array( 'fields' => 'names' ) );
		$tags       = implode( ',', $tags_array );

		$body_array = array(
			'name'          => $this->post->post_title,
			'datePublished' => get_the_modified_time( 'c', $this->post ),
			'dateCreated'   => get_the_time( 'c', $this->post ),
			'author'        => $this->get_author_name(),
			'tags'          => $tags,
			'content'       => $this->post->post_content,
		);

		$body_json = wp_json_encode( $body_array );
		$response  = wp_remote_post( $this->url,
			array(
				'method'  => 'POST',
				'timeout' => 30,
				'headers' => array(
					'Content-Type' => 'application/json',
					'token'        => $this->token,
				),
				'body'    => $body_json,
			)
		);
		return $response;
	}
}
