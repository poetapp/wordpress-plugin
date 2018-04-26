<?php
/**
 * Template for Poet Badge
 *
 * @package    Poet
 * @subpackage Poet/admin/partials
 */

?>
<div style="width: 165px; height: 50px; background-color: white; font-family: Roboto; font-size: 12px; border: 1px solid #CDCDCD; border-radius: 4px; box-shadow: 0 2px 0 0 #F0F0F0;">
	<!--   @TODO add link to po.et Work verification with target="_blank"   -->
	<div style="color: #35393E; text-decoration: none; display: flex; flex-direction: row;  height: 50px">
		<img src="<?php echo esc_attr( $quill_image_url ); ?>" style=" width: 31px; height: 31px; margin-top: 8px; margin-left: 8px; margin-right: 8px; color: #35393E; font-family: Roboto;" >
		<div>
			<p title="<?php echo esc_html( $work_id ); ?>" style="padding-top: 10px; line-height: 15px; margin: 0; font-size: 10pt; font-weight: bold; text-align: left;">
				Verified on Po.et</p>
			<p style="text-align: left; line-height: 15px; margin: 0; font-size: 10px; padding-top: 1px; font-size: 8px; font-family: Roboto; font-weight: bold; line-height: 13px; color: #707070;">
				<?php
				echo esc_html( $post_publication_date );
				?>
			</p>
		</div>
	</div>
</div>
