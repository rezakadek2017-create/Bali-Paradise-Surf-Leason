<?php
/**
 * Site footer.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$b = bpsl_business();
?>
</main>

<footer class="site-footer">
	<div class="wrap site-footer__grid">
		<div>
			<p class="site-footer__brand">Bali Paradise Surf Lesson</p>
			<p><?php echo esc_html( $b['tagline'] ); ?></p>
		</div>
		<address>
			<?php echo esc_html( $b['street'] ); ?>, <?php echo esc_html( $b['locality'] ); ?>,<br>
			<?php echo esc_html( $b['district'] ); ?>,<br>
			<?php echo esc_html( $b['regency'] ); ?>, <?php echo esc_html( $b['region'] ); ?>
			<?php if ( bpsl_opt( 'maps_url' ) ) : ?>
				<br><a href="<?php echo esc_url( bpsl_opt( 'maps_url' ) ); ?>" target="_blank" rel="noopener">Open in Google Maps</a>
			<?php endif; ?>
		</address>
		<ul class="site-footer__links">
			<li><a href="<?php echo esc_url( bpsl_wa_link( bpsl_default_wa_text() ) ); ?>" target="_blank" rel="noopener">WhatsApp us</a></li>
			<?php if ( bpsl_opt( 'email' ) ) : ?>
				<li><a href="mailto:<?php echo esc_attr( antispambot( bpsl_opt( 'email' ) ) ); ?>"><?php echo esc_html( bpsl_opt( 'email' ) ); ?></a></li>
			<?php endif; ?>
			<?php if ( bpsl_opt( 'instagram' ) ) : ?>
				<li><a href="<?php echo esc_url( bpsl_opt( 'instagram' ) ); ?>" target="_blank" rel="noopener">Instagram</a></li>
			<?php endif; ?>
			<?php if ( bpsl_opt( 'tripadvisor' ) ) : ?>
				<li><a href="<?php echo esc_url( bpsl_opt( 'tripadvisor' ) ); ?>" target="_blank" rel="noopener">TripAdvisor</a></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="wrap site-footer__legal">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Bali Paradise Surf Lesson. All rights reserved.</p>
	</div>
</footer>

<a class="wa-float" href="<?php echo esc_url( bpsl_wa_link( bpsl_default_wa_text() ) ); ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp">
	<?php echo bpsl_icon( 'whatsapp', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
</a>

<?php wp_footer(); ?>
</body>
</html>
