<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$related = $listing->get_related_listings();

if ( !$related->have_posts() ) {
	return;
}
?>

<div class="directorist-related <?php echo esc_attr( $class );?>" <?php $listing->section_id( $id ); ?>>

	<div class="directorist-related-listing-header">

		<h4><?php echo esc_html( $label );?></h4>

	</div>

	<div class='directorist-swiper' data-sw-items='3' data-sw-margin='30' data-sw-loop='true' data-sw-perslide='1' data-sw-speed='1500' data-sw-autoplay='false' data-sw-responsive='{
		"0": {"slidesPerView": "1"},
		"768": {"slidesPerView": "2"},
		"1200": {"slidesPerView": "3"}
	}'>
		<div class='swiper-wrapper'>
			<?php foreach ( $related->post_ids() as $listing_id ): ?>
				<div class='swiper-slide'>
					<?php $related->loop_template( 'grid', $listing_id ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="directorist-related-carousel" data-attr="<?php echo esc_attr( $listing->related_slider_attr() ); ?>">

		<?php foreach ( $related->post_ids() as $listing_id ): ?>

			<?php $related->loop_template( 'grid', $listing_id ); ?>

		<?php endforeach; ?>

	</div>

</div>