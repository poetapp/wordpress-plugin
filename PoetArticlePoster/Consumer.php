<?php

defined( 'ABSPATH' ) OR exit;

/**
 * Class Consumer
 * Holds the responsibility of contacting the API and exchanging data with it
 */
class Consumer {
	private $url;
	private $token;
	private $post;

	/**
	 * Consumer constructor.
	 *
	 * @param $url
	 * @param $public_key
	 * @param $private_key
	 * @param $post
	 */
	public function __construct( $url, $token, $post ) {
		$this->url      = $url;
		$this->token    = $token;
		$this->post     = $post;
	}

	/**
	 * Consume method
	 * The main method used to send articles to Po.et API
	 * @TODO body still not complete
	 * @return array|WP_Error
	 */
	public function consume() {
		$response = wp_remote_post( $this->url . '/user/claims', array(
				'method'  => 'POST',
				'timeout' => 1000,
				'headers' => array( 'content-type' => 'text/plain' ),
				'body'    => '',
			)
		);

		return $response;
	}
}