<?php
/**
 * Package price boards, driven by the "Packages" menu.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$packages = bpsl_get_posts( 'bpsl_package' );
if ( ! $packages ) {
	return;
}
?>
<section class="section section--sand" id="packages" aria-labelledby="packages-title">
	<div class="wrap">
		<header class="section__head">
			<h2 id="packages-title">Choose your lesson</h2>
			<p>Prices are shown per person unless stated. Pick a lesson, send the booking form on WhatsApp, and we confirm the day, time and beach with you.</p>
		</header>

		<div class="picker" data-picker role="group" aria-labelledby="picker-label">
			<p class="picker__label" id="picker-label">Not sure which one? How many people are surfing?</p>
			<div class="picker__opts">
				<button type="button" class="picker__opt" data-size="1" data-people="1" aria-pressed="false">Just me</button>
				<button type="button" class="picker__opt" data-size="2" data-people="2" aria-pressed="false">Two of us</button>
				<button type="button" class="picker__opt" data-size="4" data-people="4" aria-pressed="false">3&ndash;4 people</button>
				<button type="button" class="picker__opt" data-size="9" data-people="5" aria-pressed="false">5 or more</button>
			</div>
			<p class="picker__result" aria-live="polite" hidden></p>
		</div>

		<div class="packages">
			<?php
			foreach ( $packages as $p ) :
				$price = (int) bpsl_meta( $p->ID, 'price' );
				$unit  = bpsl_meta( $p->ID, 'unit' );
				$unit  = $unit ? $unit : 'person';
				$badge = bpsl_meta( $p->ID, 'badge' );
				// Largest group this lesson fits (from the "Students" field), used by the group-size picker.
				preg_match_all( '/\d+/', (string) bpsl_meta( $p->ID, 'students' ), $bpsl_nums );
				$cap = empty( $bpsl_nums[0] ) ? 0 : max( array_map( 'intval', $bpsl_nums[0] ) );
				$rows  = array(
					'Instructor' => bpsl_meta( $p->ID, 'instructor' ),
					'Students'   => bpsl_meta( $p->ID, 'students' ),
					'Duration'   => bpsl_meta( $p->ID, 'duration' ),
				);
				?>
				<article class="package<?php echo $badge ? ' package--featured' : ''; ?>" data-capacity="<?php echo (int) $cap; ?>">
					<?php if ( $badge ) : ?>
						<p class="package__badge"><?php echo esc_html( $badge ); ?></p>
					<?php endif; ?>
					<h3><?php echo esc_html( get_the_title( $p ) ); ?></h3>
					<?php if ( $price ) : ?>
						<p class="price">
							<span class="price__cur">Rp</span><span class="price__num"><?php echo esc_html( number_format( $price, 0, ',', '.' ) ); ?></span>
							<span class="price__unit">/ <?php echo esc_html( $unit ); ?></span>
						</p>
					<?php endif; ?>
					<dl class="spec">
						<?php foreach ( $rows as $label => $value ) : ?>
							<?php if ( $value ) : ?>
								<div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
							<?php endif; ?>
						<?php endforeach; ?>
					</dl>
					<div class="package__desc"><?php echo wp_kses_post( wpautop( $p->post_content ) ); ?></div>
					<a class="btn btn--sun" href="#book" data-select-package="<?php echo (int) $p->ID; ?>">Book this lesson</a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
