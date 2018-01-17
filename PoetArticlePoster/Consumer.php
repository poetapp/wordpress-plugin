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
	 * @param token
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
	 * @return array|WP_Error
	 */
	public function consume() {

        $user       = get_user_by( 'ID', $this->post->post_author );
        $tags_array = wp_get_post_tags( $this->post->ID, array( 'fields' => 'names' ) );
        $tags       = implode(',', $tags_array);
        $author     = $user->display_name;

        if ( ! empty( $user->first_name ) ) {
            $author = $user->first_name;
            if ( ! empty( $user->last_name ) ) {
                $author .= ' ' . $user->last_name;
            }
        } else if ( ! empty( $user->last_name ) ) {
            $author = $user->last_name;
        }

        $body_array = array(
	        'name'          => $this->post->post_title,
            'datePublished' => get_the_modified_time( 'c', $this->post ),
            'dateCreated'   => get_the_time( 'c', $this->post ),
            'author'        => $author,
            'tags'          => $tags,
            'content'       => $this->post->post_content
        );
	    $body_json = wp_json_encode( $body_array );

		$response = wp_remote_post( $this->url, array(
				'method'  => 'POST',
				'timeout' => 30,
				'headers' => array( 'Content-Type'  => 'application/json',
                                    'token'         => $this->token ),
				'body'    => $body_json,
			)
		);

		return $response;
	}
}