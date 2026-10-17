<?php
/**
 * Business data + helpers. Verified numbers live here, not in templates.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Verified business facts. Do not change unless the owner says so. */
function bpsl_business() {
	return array(
		'name'     => 'Bali Paradise Surf Lesson',
		'tagline'  => '100% Balinese Owned & Operated',
		'street'   => 'Jl. Arjuna',
		'locality' => 'Seminyak',
		'district' => 'Kec. Kuta',
		'regency'  => 'Kabupaten Badung',
		'region'   => 'Bali',
		'country'  => 'ID',
		'rating'   => '4.9',
		'reviews'  => 300,
	);
}

function bpsl_stats() {
	return array(
		array( 'value' => '2,500+', 'label' => 'Surfers taught' ),
		array( 'value' => '10+', 'label' => 'Years experience' ),
		array( 'value' => '4.9/5', 'label' => 'Customer rating' ),
		array( 'value' => '98%', 'label' => 'Stand up on lesson one' ),
	);
}

/** Editable defaults (Appearance > Customize > Surf School Settings). */
function bpsl_defaults() {
	return array(
		'whatsapp'         => '6281394265915',
		'email'            => '',
		'instagram'        => 'https://www.instagram.com/gede_rusmawan?igsh=MWI2M3BkM3V0dTg0aw==',
		'tripadvisor'      => '',
		'google_reviews'   => '',
		'maps_url'         => 'https://www.google.com/maps/dir/?api=1&destination=Bali%20Paradise%20Surf%20Lesson%2C%20Jl.%20Arjuna%2C%20Seminyak%2C%20Kec.%20Kuta%2C%20Kabupaten%20Badung%2C%20Bali',
		'hero_image'       => 0,
		'hero_title'       => 'Catch Your First Wave with True Balinese Locals',
		'hero_text'        => 'Friendly, authentic surf lessons in Bali for all ages and skill levels. By surfing with us, you directly support local families and village communities.',
		'meta_description' => 'Friendly, authentic surf lessons in Bali for all ages and skill levels. 100% Balinese owned and operated in Seminyak. Book via WhatsApp.',
		'included'         => "Premium soft-top surfboard & safety leash\nUV rash guard provided\nLocal Balinese instructor for your whole lesson\nBeach safety briefing & ocean awareness\nPop-up technique drills on the sand\n15-minute hydration & chill break",
		'bring'            => "Swimwear\nTowel\nSunscreen\nSmile — we handle the rest!",
	);
}

function bpsl_opt( $key ) {
	$defaults = bpsl_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	return get_theme_mod( 'bpsl_' . $key, $default );
}

function bpsl_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
}

function bpsl_money( $amount ) {
	return 'Rp' . number_format( (float) $amount, 0, ',', '.' );
}

function bpsl_wa_number() {
	return preg_replace( '/\D+/', '', (string) bpsl_opt( 'whatsapp' ) );
}

function bpsl_wa_link( $text = '' ) {
	$number = bpsl_wa_number();
	$url    = $number ? 'https://wa.me/' . $number : 'https://wa.me/';
	if ( $text ) {
		$url .= '?text=' . rawurlencode( $text );
	}
	return $url;
}

function bpsl_default_wa_text() {
	return "Hello Bali Paradise Surf Lesson! I'd like to book a surf lesson.";
}

/** Cached query for our custom post types. */
function bpsl_get_posts( $type, $limit = -1 ) {
	static $cache = array();
	$key = $type . ':' . $limit;
	if ( ! isset( $cache[ $key ] ) ) {
		$cache[ $key ] = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'publish',
				'posts_per_page' => $limit,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'  => true,
			)
		);
	}
	return $cache[ $key ];
}

function bpsl_meta( $post_id, $key ) {
	return get_post_meta( $post_id, '_bpsl_' . $key, true );
}

/** Anchor URL that works from any page. */
function bpsl_anchor( $id ) {
	return is_front_page() ? '#' . $id : home_url( '/#' . $id );
}
