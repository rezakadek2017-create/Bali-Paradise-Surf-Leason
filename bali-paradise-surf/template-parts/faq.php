<?php
/**
 * FAQ using native <details> (accessible, works without JavaScript).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$faqs = bpsl_get_posts( 'bpsl_faq' );
if ( ! $faqs ) {
	return;
}
?>
<section class="section section--sand" id="faq" aria-labelledby="faq-title">
	<div class="wrap faq">
		<div class="faq__intro">
			<h2 id="faq-title">Questions before you book</h2>
			<label class="faq__searchbox">
				<span class="screen-reader-text">Search the questions</span>
				<input type="search" class="faq__search" placeholder="Search, e.g. kids, price, swim" autocomplete="off">
			</label>
			<p class="faq__empty" hidden>No question matches that. <a href="<?php echo esc_url( bpsl_wa_link( bpsl_default_wa_text() ) ); ?>" target="_blank" rel="noopener">Ask us on WhatsApp</a>.</p>
		</div>
		<div class="faq__list">
			<?php foreach ( $faqs as $f ) : ?>
				<details>
					<summary><?php echo esc_html( get_the_title( $f ) ); ?></summary>
					<div class="faq__answer"><?php echo wp_kses_post( wpautop( $f->post_content ) ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
