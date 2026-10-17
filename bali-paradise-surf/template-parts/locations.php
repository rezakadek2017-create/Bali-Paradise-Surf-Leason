<?php
/**
 * Surf spots, driven by the "Locations" menu.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$locations = bpsl_get_posts( 'bpsl_location' );
if ( ! $locations ) {
	return;
}

// Google Maps direction links per location
$maps_links = array(
	'Seminyak Beach' => 'https://www.google.com/maps/dir/?api=1&destination=Bali+Paradise+Surf+Lesson,+Jl.+Arjuna,+Seminyak,+Kec.+Kuta,+Kabupaten+Badung,+Bali',
	'Kuta Beach'     => 'https://www.google.com/maps/dir/?api=1&destination=Kuta+Beach,+Bali',
	'Canggu'         => 'https://www.google.com/maps/dir/?api=1&destination=Batu+Bolong+Beach,+Canggu,+Bali',
);

// Detail data per location
$location_details = array(
	'Seminyak Beach' => array(
		'address'      => 'Jl. Arjuna, Seminyak, Kec. Kuta, Badung, Bali',
		'wave_type'    => 'Gentle white-water, sandy bottom',
		'best_for'     => 'First-timers & improvers',
		'getting_there' => '5 min walk from Double Six street',
	),
	'Kuta Beach' => array(
		'address'      => 'Pantai Kuta, Kec. Kuta, Kabupaten Badung, Bali',
		'wave_type'    => 'Soft, consistent white-water',
		'best_for'     => 'Complete beginners & families',
		'getting_there' => '10 min south from Seminyak base',
	),
	'Canggu' => array(
		'address'      => 'Pantai Batu Bolong, Canggu, Kec. Kuta Utara, Bali',
		'wave_type'    => 'Medium rolling waves, reef & sand',
		'best_for'     => 'Improvers & intermediate surfers',
		'getting_there' => '20 min north from Seminyak base',
	),
);

// Spots shown on the interactive map (chips + "Show on map" buttons).
$spots = array(
	array(
		'label' => 'Seminyak (home base)',
		'name'  => 'Bali Paradise Surf Lesson',
		'q'     => 'Bali Paradise Surf Lesson, Jl. Arjuna, Seminyak, Kec. Kuta, Kabupaten Badung, Bali',
		'addr'  => 'Jl. Arjuna, Seminyak, Kec. Kuta, Kabupaten Badung, Bali 80361',
		'dir'   => 'https://www.google.com/maps/dir/?api=1&destination=Bali+Paradise+Surf+Lesson,+Jl.+Arjuna,+Seminyak,+Kec.+Kuta,+Kabupaten+Badung,+Bali',
	),
	array(
		'label' => 'Kuta Beach',
		'name'  => 'Kuta Beach',
		'q'     => 'Pantai Kuta, Kuta, Kabupaten Badung, Bali',
		'addr'  => 'Pantai Kuta, Kec. Kuta, Kabupaten Badung, Bali',
		'dir'   => 'https://www.google.com/maps/dir/?api=1&destination=Kuta+Beach,+Bali',
	),
	array(
		'label' => 'Canggu',
		'name'  => 'Batu Bolong Beach, Canggu',
		'q'     => 'Pantai Batu Bolong, Canggu, Kuta Utara, Bali',
		'addr'  => 'Pantai Batu Bolong, Canggu, Kec. Kuta Utara, Bali',
		'dir'   => 'https://www.google.com/maps/dir/?api=1&destination=Batu+Bolong+Beach,+Canggu,+Bali',
	),
);
$spot_index = array(
	'Seminyak Beach' => 0,
	'Kuta Beach'     => 1,
	'Canggu'         => 2,
);

// Fallback images
$location_images = array(
	0 => '/assets/img/seminyak_beach.jpg',
	1 => '/assets/img/kuta_beach.jpg',
	2 => '/assets/img/canggu_beach.jpg',
);
?>
<section class="section" id="locations" aria-labelledby="locations-title">
	<div class="wrap">
		<header class="section__head">
			<h2 id="locations-title">Where we surf</h2>
			<p>We choose the beach on the day from the swell, tide and your level, then confirm it with you on WhatsApp.</p>
		</header>
		<div class="locations">
			<?php foreach ( $locations as $i => $loc ) :
				$title       = get_the_title( $loc );
				$maps_url    = isset( $maps_links[ $title ] ) ? $maps_links[ $title ] : '#';
				$details     = isset( $location_details[ $title ] ) ? $location_details[ $title ] : null;
			?>
				<article class="location">
					<div class="location__media location__media--<?php echo (int) ( $i % 3 ); ?>">
						<?php
						if ( has_post_thumbnail( $loc ) ) {
							echo get_the_post_thumbnail( $loc, 'bpsl-card', array( 'loading' => 'lazy', 'decoding' => 'async' ) );
						} else {
							$fallback_src = isset( $location_images[ $i % 3 ] ) ? get_template_directory_uri() . $location_images[ $i % 3 ] : '';
							if ( $fallback_src && file_exists( get_template_directory() . $location_images[ $i % 3 ] ) ) {
								echo '<img src="' . esc_url( $fallback_src ) . '" alt="' . esc_attr( $title ) . '" class="bpsl-thumb" loading="lazy" decoding="async">';
							} else {
								echo bpsl_icon( 'wave', 56 ); // phpcs:ignore WordPress.Security.EscapeOutput
							}
						}
						?>
					</div>
					<div class="location__body">
						<h3><?php echo esc_html( $title ); ?></h3>
						<?php if ( bpsl_meta( $loc->ID, 'level' ) ) : ?>
							<p class="location__level"><?php echo bpsl_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( bpsl_meta( $loc->ID, 'level' ) ); ?></p>
						<?php endif; ?>
						<?php echo wp_kses_post( wpautop( $loc->post_content ) ); ?>

						<?php if ( $details ) : ?>
						<dl class="location__details">
							<div><dt>Address</dt><dd><?php echo esc_html( $details['address'] ); ?></dd></div>
							<div><dt>Wave Type</dt><dd><?php echo esc_html( $details['wave_type'] ); ?></dd></div>
							<div><dt>Best For</dt><dd><?php echo esc_html( $details['best_for'] ); ?></dd></div>
							<div><dt>Getting There</dt><dd><?php echo esc_html( $details['getting_there'] ); ?></dd></div>
						</dl>
						<?php endif; ?>

						<div class="location__actions">
							<?php if ( isset( $spot_index[ $title ] ) ) : ?>
								<button type="button" class="location__maps-link" data-map-spot="<?php echo (int) $spot_index[ $title ]; ?>"><?php echo bpsl_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Show on map</button>
							<?php endif; ?>
							<a class="location__maps-link location__maps-link--alt" href="<?php echo esc_url( $maps_url ); ?>" target="_blank" rel="noopener">Directions</a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<!-- Interactive Google Maps embed (no API key). Chips and card buttons switch the beach. -->
		<div class="location-map" id="find-us" data-map data-spots="<?php echo esc_attr( wp_json_encode( $spots ) ); ?>">
			<div class="location-map__header">
				<h3><?php echo bpsl_icon( 'pin', 24 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Find Us &mdash; Bali Paradise Surf Lesson</h3>
				<p>Our home base is on <strong>Jl. Arjuna, Seminyak Beach</strong> (near Double Six). Pick a beach to see where we may meet you.</p>
				<div class="map-chips" role="group" aria-label="Choose a beach to show on the map">
					<?php foreach ( $spots as $i => $spot ) : ?>
						<button type="button" class="map-chip" data-map-chip="<?php echo (int) $i; ?>" aria-pressed="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $spot['label'] ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="location-map__embed is-locked">
				<iframe class="location-map__frame" src="https://www.google.com/maps?q=<?php echo esc_attr( rawurlencode( $spots[0]['q'] ) ); ?>&amp;z=15&amp;output=embed" title="Map: <?php echo esc_attr( $spots[0]['name'] ); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
				<button type="button" class="location-map__lock"><span>Tap to explore the map</span></button>
			</div>
			<div class="location-map__bar">
				<div class="location-map__info">
					<p class="location-map__name" data-map-name><?php echo esc_html( $spots[0]['name'] ); ?></p>
					<p class="location-map__addr" data-map-addr><?php echo esc_html( $spots[0]['addr'] ); ?></p>
					<p class="location-map__rating" data-map-rating><?php echo bpsl_icon( 'star', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <strong>4.9</strong> <span>(300+ reviews)</span></p>
				</div>
				<div class="location-map__actions">
					<a class="btn btn--sun btn--sm" data-map-dir href="<?php echo esc_url( $spots[0]['dir'] ); ?>" target="_blank" rel="noopener"><?php echo bpsl_icon( 'pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Open in Google Maps</a>
					<a class="btn btn--sea btn--sm" href="<?php echo esc_url( bpsl_wa_link( "Hello Bali Paradise Surf Lesson! I'd like directions to your location." ) ); ?>" target="_blank" rel="noopener"><?php echo bpsl_icon( 'whatsapp', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?> Ask for directions</a>
				</div>
			</div>
		</div>
	</div>
</section>
