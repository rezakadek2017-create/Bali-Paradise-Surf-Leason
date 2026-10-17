<?php
/**
 * How a lesson works. This is a true sequence, so numbered steps are correct.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$steps = array(
	array( 'Send your details', 'Fill in the booking form below. It opens WhatsApp with your message ready to send.' ),
	array( 'We confirm', 'We reply with the day, time and beach that suit the waves and your level.' ),
	array( 'Learn on the sand', 'Safety briefing, surf etiquette and your first pop-ups before you enter the water.' ),
	array( 'Catch waves', 'Your instructor stays with you in the water until you are riding.' ),
);
?>
<section class="section section--sea" id="process" aria-labelledby="process-title">
	<div class="wrap">
		<header class="section__head section__head--light">
			<h2 id="process-title">From message to first wave</h2>
		</header>
		<ol class="steps">
			<?php foreach ( $steps as $i => $s ) : ?>
				<li>
					<span class="steps__num" aria-hidden="true"><?php echo (int) ( $i + 1 ); ?></span>
					<h3><?php echo esc_html( $s[0] ); ?></h3>
					<p><?php echo esc_html( $s[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
