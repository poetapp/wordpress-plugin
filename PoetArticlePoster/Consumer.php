<?php

defined( 'ABSPATH' ) OR exit;

/**
 * Class Consumer
 * Holds the responsibility of contacting the API and exchanging data with it
 */
class Consumer {
	private $url;
	private $public_key;
	private $private_key;
	private $post;

	/**
	 * Consumer constructor.
	 *
	 * @param $url
	 * @param $public_key
	 * @param $private_key
	 * @param $post
	 */
	public function __construct( $url, $public_key, $private_key, $post ) {
		$this->url         = $url;
		$this->public_key  = $public_key;
		$this->private_key = $private_key;
		$this->post        = $post;
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