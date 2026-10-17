<?php
/**
 * Hero: photo-led, with verified numbers along the bottom edge.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$hero_id = (int) bpsl_opt( 'hero_image' );
$b       = bpsl_business();
?>
<section class="hero" aria-labelledby="hero-title">
	<div class="hero__media" data-parallax aria-hidden="<?php echo $hero_id ? 'false' : 'true'; ?>">
		<?php if ( $hero_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$hero_id,
				'bpsl-hero',
				false,
				array(
					'class'         => 'hero__img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
					'decoding'      => 'async',
				)
			);
			?>
		<?php elseif ( file_exists( get_template_directory() . '/assets/img/img_1782418084454_213ebc94.webp' ) ) : ?>
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/img_1782418084454_213ebc94.webp' ); ?>" class="hero__img" alt="Bali Paradise Surf Lesson Seminyak" loading="eager" fetchpriority="high" decoding="async">
		<?php else : ?>
			<svg class="hero__scene" viewBox="0 0 1440 900" preserveAspectRatio="xMidYMid slice" focusable="false">
				<defs>
					<linearGradient id="sky" x1="0" y1="0" x2="0" y2="1">
						<stop offset="0" stop-color="#062A30"/>
						<stop offset=".55" stop-color="#0F6670"/>
						<stop offset="1" stop-color="#F5A800"/>
					</linearGradient>
				</defs>
				<rect width="1440" height="900" fill="url(#sky)"/>
				<circle cx="1040" cy="560" r="150" fill="#FFD27A" opacity=".95"/>
				<path d="M0 600c180-40 320 30 520 0s380-60 560-10 260 20 360-10v320H0z" fill="#0F6670" opacity=".9"/>
				<path d="M0 680c200-50 360 40 580 0s400-50 560 0 200 20 300-10v240H0z" fill="#0B4A52"/>
				<path d="M0 770c220-40 380 30 600 0s420-40 580 0 180 10 260-10v150H0z" fill="#083840"/>
			</svg>
		<?php endif; ?>
	</div>

	<div class="wrap hero__inner">
		<p class="hero__badge"><?php echo bpsl_icon( 'flower', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $b['tagline'] ); ?></p>
		<h1 id="hero-title"><?php echo esc_html( bpsl_opt( 'hero_title' ) ); ?></h1>
		<p class="hero__lead"><?php echo esc_html( bpsl_opt( 'hero_text' ) ); ?></p>
		<div class="hero__actions">
			<a class="btn btn--sun" href="#book"><?php echo bpsl_icon( 'whatsapp', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Book via WhatsApp</a>
			<a class="btn btn--ghost" href="#packages">See lessons and prices</a>
		</div>
	</div>

	<div class="wrap hero__stats">
		<dl class="stats">
			<?php foreach ( bpsl_stats() as $stat ) : ?>
				<div class="stats__item">
					<dt><?php echo esc_html( $stat['label'] ); ?></dt>
					<dd><?php echo esc_html( $stat['value'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
	</div>

	<div class="waves" aria-hidden="true">
		<svg class="waves__back" viewBox="0 0 2880 80" preserveAspectRatio="none" focusable="false"><path d="M0 40Q360 0 720 40T1440 40T2160 40T2880 40V80H0Z"/></svg>
		<svg class="waves__front" viewBox="0 0 2880 80" preserveAspectRatio="none" focusable="false"><path d="M0 40Q360 0 720 40T1440 40T2160 40T2880 40V80H0Z"/></svg>
	</div>
</section>
