<?php
/**
 * Social proof: verified rating plus guest quotes added in the dashboard.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$b      = bpsl_business();
$quotes = bpsl_get_posts( 'bpsl_testimonial' );
?>
<section class="section" id="reviews" aria-labelledby="reviews-title">
	<div class="wrap reviews">
		<div class="reviews__score">
			<h2 id="reviews-title" class="screen-reader-text">Guest reviews</h2>
			<p class="reviews__big"><?php echo esc_html( $b['rating'] ); ?><span>/5</span></p>
			<p class="stars" aria-hidden="true"><?php echo bpsl_icon( 'star', 22 ) . bpsl_icon( 'star', 22 ) . bpsl_icon( 'star', 22 ) . bpsl_icon( 'star', 22 ) . bpsl_icon( 'star', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
			<p>From <?php echo esc_html( $b['reviews'] ); ?>+ guests</p>
			<?php if ( bpsl_opt( 'tripadvisor' ) || bpsl_opt( 'google_reviews' ) ) : ?>
				<p class="reviews__links">
					<?php if ( bpsl_opt( 'google_reviews' ) ) : ?>
						<a href="<?php echo esc_url( bpsl_opt( 'google_reviews' ) ); ?>" target="_blank" rel="noopener">Read Google reviews</a>
					<?php endif; ?>
					<?php if ( bpsl_opt( 'tripadvisor' ) ) : ?>
						<a href="<?php echo esc_url( bpsl_opt( 'tripadvisor' ) ); ?>" target="_blank" rel="noopener">Read TripAdvisor reviews</a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( $quotes ) : ?>
			<div class="quotes">
				<?php foreach ( $quotes as $q ) : ?>
					<?php $rating = (int) bpsl_meta( $q->ID, 'rating' ); ?>
					<figure class="quote">
						<?php if ( $rating ) : ?>
							<p class="screen-reader-text"><?php echo esc_html( $rating ); ?> out of 5 stars</p>
						<?php endif; ?>
						<blockquote><?php echo wp_kses_post( wpautop( $q->post_content ) ); ?></blockquote>
						<figcaption>
							<?php echo esc_html( get_the_title( $q ) ); ?>
							<?php if ( bpsl_meta( $q->ID, 'origin' ) ) : ?>
								<span>, <?php echo esc_html( bpsl_meta( $q->ID, 'origin' ) ); ?></span>
							<?php endif; ?>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		<?php elseif ( current_user_can( 'edit_posts' ) ) : ?>
			<p class="admin-note">No guest quotes yet (visible to editors only). Add real guest reviews under <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=bpsl_testimonial' ) ); ?>">Testimonials</a>.</p>
		<?php endif; ?>
	</div>
</section>
