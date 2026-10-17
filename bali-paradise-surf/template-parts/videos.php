<?php
/**
 * Videos. YouTube loads only after a click (privacy + speed): no third-party
 * scripts run until the visitor presses play.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$videos = array(
	array( 'pGcHOmPSWAI', 'Double Six Beach surf' ),
	array( '2Xj8tdiWoKY', 'Surfing at Double Six Beach, Bali, 2018' ),
);
?>
<section class="section section--sea" id="videos" aria-labelledby="videos-title">
	<div class="wrap">
		<header class="section__head section__head--light">
			<h2 id="videos-title">See the stoke in action</h2>
			<p>A look at the waves where we teach.</p>
		</header>
		<div class="videos">
			<?php foreach ( $videos as $v ) : ?>
				<article class="video">
					<div class="video__frame">
						<button type="button" class="video__play" data-yt="<?php echo esc_attr( $v[0] ); ?>" data-title="<?php echo esc_attr( $v[1] ); ?>" aria-label="<?php echo esc_attr( 'Play video: ' . $v[1] ); ?>">
							<img src="https://i.ytimg.com/vi/<?php echo esc_attr( $v[0] ); ?>/hqdefault.jpg" alt="" loading="lazy" decoding="async">
							<span aria-hidden="true"><svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></span>
						</button>
					</div>
					<h3><?php echo esc_html( $v[1] ); ?></h3>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
