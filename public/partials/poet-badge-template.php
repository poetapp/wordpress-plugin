<?php
/**
 * Template for Poet Badge
 *
 * @package    Poet
 * @subpackage Poet/admin/partials
 */

/**
 * Filter to add Verified by Po.et badge  to the post thumnails
 *
 * @param string $quill_image_url image URL.
 * @param string $work_id  post ID on Poet.
 * @param string $post_publication_date date of the post publication.
 * @return string html code plus Po.et badge
 */
function print_poet_template( $quill_image_url, $work_id, $post_publication_date ) {
	ob_start()
	?>
	<div class = "poet-container">
		<a href="https://explorer.po.et/works/<?php echo esc_html( $work_id ); ?>" target="_blank">
			<div class = "poet-inner">
				<img src="<?php echo esc_attr( $quill_image_url ); ?>" class = "poet-image" >
				<div>
					<p title="<?php echo esc_html( $work_id ); ?>" class = "poet-title">
						Verified on Po.et</p>
					<p class = "poet-date">
						<?php
						echo esc_html( $post_publication_date );
						?>
					</p>
				</div>
			</div>
		</a>
	</div>
	<?php
	return ob_get_clean();
}
