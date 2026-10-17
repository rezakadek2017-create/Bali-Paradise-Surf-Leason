<?php
/**
 * Appearance > Customize > Surf School Settings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Accept 08123..., +62 812..., 62812... and store digits in international form. */
function bpsl_sanitize_phone( $value ) {
	$digits = preg_replace( '/\D+/', '', (string) $value );
	if ( 0 === strpos( $digits, '00' ) ) {
		$digits = substr( $digits, 2 );
	}
	if ( 0 === strpos( $digits, '0' ) ) {
		$digits = '62' . substr( $digits, 1 );
	}
	return $digits;
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$defaults = bpsl_defaults();

		$wp_customize->add_section(
			'bpsl_settings',
			array(
				'title'    => 'Surf School Settings',
				'priority' => 30,
			)
		);

		$fields = array(
			'whatsapp'         => array( 'WhatsApp number', 'text', 'bpsl_sanitize_phone', 'International format, e.g. 6281234567890 (a leading 0 becomes 62). Every booking button uses this number.' ),
			'email'            => array( 'Email (optional)', 'text', 'sanitize_email', '' ),
			'instagram'        => array( 'Instagram URL', 'url', 'esc_url_raw', '' ),
			'tripadvisor'      => array( 'TripAdvisor URL', 'url', 'esc_url_raw', '' ),
			'google_reviews'   => array( 'Google reviews URL', 'url', 'esc_url_raw', '' ),
			'maps_url'         => array( 'Google Maps URL', 'url', 'esc_url_raw', '' ),
			'hero_title'       => array( 'Hero headline', 'text', 'sanitize_text_field', '' ),
			'hero_text'        => array( 'Hero text', 'textarea', 'sanitize_textarea_field', '' ),
			'meta_description' => array( 'Google description (SEO)', 'textarea', 'sanitize_textarea_field', 'Around 150 characters.' ),
			'included'         => array( 'What\'s included (one per line)', 'textarea', 'sanitize_textarea_field', '' ),
			'bring'            => array( 'What to bring (one per line)', 'textarea', 'sanitize_textarea_field', '' ),
		);

		foreach ( $fields as $key => $f ) {
			$wp_customize->add_setting(
				'bpsl_' . $key,
				array(
					'default'           => $defaults[ $key ],
					'sanitize_callback' => $f[2],
				)
			);
			$wp_customize->add_control(
				'bpsl_' . $key,
				array(
					'label'       => $f[0],
					'description' => $f[3],
					'section'     => 'bpsl_settings',
					'type'        => $f[1],
				)
			);
		}

		$wp_customize->add_setting(
			'bpsl_hero_image',
			array(
				'default'           => 0,
				'sanitize_callback' => 'absint',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'bpsl_hero_image',
				array(
					'label'       => 'Hero photo',
					'description' => 'Landscape surf photo, at least 2000px wide. Without it, an illustrated sunrise is shown.',
					'section'     => 'bpsl_settings',
					'mime_type'   => 'image',
				)
			)
		);
	}
);
