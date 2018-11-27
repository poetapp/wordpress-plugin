<?php

/**
 * Feature to backfill old posts to Frost API.
 *
 * @TODO Make a WP-CLI for this as well so one could batch process tens/hundreds of thousands of posts
 *
 * @package    Poet
 * @subpackage Poet/includes
 */
class Poet_Backfill {

    /**
     * Schedule a cron to backfill posts if the user has this feature enabled.
     */
    public static function init() {
        $should_backfill = isset( get_option( 'poet_option' )['active'] ) ? 1 : 0;

        if( $should_backfill ) {
            if (! wp_next_scheduled ( 'poet_backfill_posts' )) {
                wp_schedule_event(time(), 'hourly', 'poet_backfill_posts');
            }
        }

        add_action( 'poet_backfill_posts', [__CLASS__, 'backfill_posts'] );

    }

    /**
     * Backfill posts to the Frost API
     */
    public static function backfill_posts() {

        // Only running 100 at a time so the jobs don't back up or spike host resources
        $query_args = [
            'posts_per_page' => 100,
            'post_status' => 'publish',
            'no_found_rows' => true,
            'meta_query' => [
                [
                    'key'     => 'poet_work_id',
			        'compare' => 'NOT EXISTS',
                ],
            ],
            'fields' => 'ids',
        ];

        $backfill_posts = new WP_Query($query_args);
 
        if( !empty( $backfill_posts->posts ) ) {
            foreach( $backfill_posts->posts as $backfill_post_id ) {
                \Poet_Public::post_article( $backfill_post_id );
            }
        }
    }

}