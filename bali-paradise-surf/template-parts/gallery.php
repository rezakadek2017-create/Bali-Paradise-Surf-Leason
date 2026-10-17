<?php
/**
 * Photo gallery with an accessible <dialog> lightbox.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$items = array_filter(
	bpsl_get_posts( 'bpsl_gallery' ),
	function ( $p ) {
		return has_post_thumbnail( $p );
	}
);

$fallback_gallery = array(
	array( '/assets/img/img_1782418083920_ea123930.webp', 'Surf lesson action in Seminyak' ),
	array( '/assets/img/img_1782418083765_bfe16419.webp', 'Happy surfers smiling with boards' ),
	array( '/assets/img/img_1782418083849_5c252b61.webp', 'Catching gentle waves on Canggu beach' ),
	array( '/assets/img/img_1782418084140_24da6cc3.webp', 'Group surf lesson fun in the water' ),
	array( '/assets/img/img_1782418083659_2b0af617.webp', 'Patient Balinese instructor guidance' ),
	array( '/assets/img/img_1782418083709_d0e16f99.webp', 'Golden hour sunset surf session' ),
	array( '/assets/img/img_1782418084010_63078912.webp', 'Joy of standing up on the first wave' ),
	array( '/assets/img/img_1782418084233_77530d6c.webp', 'Authentic Bali surf lifestyle' ),
	array( '/assets/img/img_1782418083587_fa412684.webp', 'Bali Paradise Surf Lesson guests on the beach' ),
	array( '/assets/img/img_1782418083958_48aa7394.webp', 'Beginner surf lesson in Seminyak' ),
	array( '/assets/img/img_1782418084298_0ac5750d.webp', 'Surfers practising with their Balinese instructor' ),
	array( '/assets/img/img_1782418084373_a3579dbe.webp', 'Riding a small wave in Bali' ),
	array( '/assets/img/img_1782418084454_213ebc94.webp', 'Surf lesson with Bali Paradise Surf Lesson' ),
);

// Which tiles are "feature" (2x2) tiles. total % 4 feature tiles keep a 4-column grid free of holes.
$bpsl_total   = $items ? count( $items ) : count( $fallback_gallery );
$bpsl_feat_n  = $bpsl_total % 4;
$bpsl_feature = array();
for ( $k = 0; $k < $bpsl_feat_n; $k++ ) {
	$bpsl_feature[] = (int) floor( $k * $bpsl_total / $bpsl_feat_n );
}
$bpsl_i = -1;

if ( ! $items && empty( $fallback_gallery ) ) {
	if ( current_user_can( 'edit_posts' ) ) {
		?>
		<section class="section" id="gallery"><div class="wrap"><p class="admin-note">Gallery is empty and hidden from visitors. Add photos under <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=bpsl_gallery' ) ); ?>">Gallery</a> (set each photo as the Featured image).</p></div></section>
		<?php
	}
	return;
}
?>
<section class="section section--sand" id="gallery" aria-labelledby="gallery-title">
	<div class="wrap">
		<header class="section__head">
			<h2 id="gallery-title">Real lessons, real Bali</h2>
		</header>
		<ul class="gallery">
			<?php if ( $items ) : ?>
				<?php foreach ( $items as $p ) : ?>
					<?php
					$thumb_id = get_post_thumbnail_id( $p );
					$full     = wp_get_attachment_image_url( $thumb_id, 'large' );
					$alt      = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true );
					$alt      = $alt ? $alt : get_the_title( $p );
					?>
					<?php ++$bpsl_i; ?>
					<li<?php echo in_array( $bpsl_i, $bpsl_feature, true ) ? ' class="is-feature"' : ''; ?>>
						<a href="<?php echo esc_url( $full ); ?>" data-lightbox data-alt="<?php echo esc_attr( $alt ); ?>">
							<?php
							echo wp_get_attachment_image(
								$thumb_id,
								'bpsl-gallery',
								false,
								array(
									'alt'      => $alt,
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						</a>
					</li>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach ( $fallback_gallery as $fb ) : ?>
					<?php
					$src = get_template_directory_uri() . $fb[0];
					$alt = $fb[1];
					?>
					<?php ++$bpsl_i; ?>
					<li<?php echo in_array( $bpsl_i, $bpsl_feature, true ) ? ' class="is-feature"' : ''; ?>>
						<a href="<?php echo esc_url( $src ); ?>" data-lightbox data-alt="<?php echo esc_attr( $alt ); ?>">
							<img src="<?php echo esc_url( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" class="bpsl-thumb" loading="lazy" decoding="async">
						</a>
					</li>
				<?php endforeach; ?>
			<?php endif; ?>
		</ul>
	</div>
	<dialog class="lightbox" id="lightbox" aria-label="Photo viewer">
		<button class="lightbox__close" type="button" aria-label="Close photo"><?php echo bpsl_icon( 'close', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></button>
		<figure>
			<img src="" alt="">
			<button class="lightbox__nav lightbox__nav--prev" type="button" aria-label="Previous photo"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 5l-7 7 7 7"/></svg></button>
			<button class="lightbox__nav lightbox__nav--next" type="button" aria-label="Next photo"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg></button>
			<figcaption class="lightbox__caption"><span class="lightbox__cap"></span><span class="lightbox__count" aria-live="polite"></span></figcaption>
		</figure>
	</dialog>
</section>
