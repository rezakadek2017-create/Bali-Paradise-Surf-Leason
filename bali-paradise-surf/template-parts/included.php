<?php
/**
 * What's included / what to bring (Customizer text).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$included = bpsl_lines( bpsl_opt( 'included' ) );
$bring    = bpsl_lines( bpsl_opt( 'bring' ) );
if ( ! $included && ! $bring ) {
	return;
}
?>
<section class="section" id="included" aria-labelledby="included-title">
	<div class="wrap included">
		<h2 id="included-title">What is included</h2>
		<?php if ( $included ) : ?>
			<ul class="checklist">
				<?php foreach ( $included as $item ) : ?>
					<li><?php echo bpsl_icon( 'check', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php echo esc_html( $item ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( $bring ) : ?>
			<div class="bring">
				<h3>Bring with you</h3>
				<ul>
					<?php foreach ( $bring as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
