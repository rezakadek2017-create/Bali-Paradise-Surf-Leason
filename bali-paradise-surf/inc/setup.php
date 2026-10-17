<?php
/**
 * Theme setup, assets, performance.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 80,
				'width'       => 240,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);
		add_image_size( 'bpsl-card', 800, 600, true );
		add_image_size( 'bpsl-gallery', 900, 900, false );
		add_image_size( 'bpsl-hero', 2000, 1200, false );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$css = BPSL_DIR . '/assets/css/main.css';
		$js  = BPSL_DIR . '/assets/js/main.js';

		wp_enqueue_style( 'bpsl-main', BPSL_URI . '/assets/css/main.css', array(), file_exists( $css ) ? filemtime( $css ) : BPSL_VERSION );
		wp_enqueue_script(
			'bpsl-main',
			BPSL_URI . '/assets/js/main.js',
			array(),
			file_exists( $js ) ? filemtime( $js ) : BPSL_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		// The landing page is fully custom: skip block CSS for speed.
		if ( is_front_page() ) {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'global-styles' );
			wp_dequeue_style( 'classic-theme-styles' );
		}
	},
	100
);

/** Remove emoji scripts (not needed, saves requests). */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/** Mark the document as JS-capable before first paint (progressive enhancement). */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.className+=' js';</script>\n";
	},
	1
);

/** Preload the hero image for a faster LCP. */
add_action(
	'wp_head',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		$id = (int) bpsl_opt( 'hero_image' );
		if ( $id ) {
			$src = wp_get_attachment_image_url( $id, 'bpsl-hero' );
			if ( $src ) {
				printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $src ) );
			}
		}
	},
	2
);

/** Admin hints so the owner knows what is left to fill in. */
add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->base, array( 'dashboard', 'themes' ), true ) ) {
			return;
		}
		$todo = array();
		if ( ! bpsl_wa_number() ) {
			$todo[] = sprintf(
				/* translators: %s: customizer URL */
				__( 'Add your WhatsApp number in <a href="%s">Appearance &rarr; Customize &rarr; Surf School Settings</a>. Until then, booking buttons cannot reach you.', 'bpsl' ),
				esc_url( admin_url( 'customize.php?autofocus[section]=bpsl_settings' ) )
			);
		}
		if ( count( bpsl_get_posts( 'bpsl_package' ) ) < 2 ) {
			$todo[] = sprintf(
				/* translators: %s: packages admin URL */
				__( 'Only one surf package is published. Add the others under <a href="%s">Packages</a>.', 'bpsl' ),
				esc_url( admin_url( 'edit.php?post_type=bpsl_package' ) )
			);
		}
		if ( ! $todo ) {
			return;
		}
		echo '<div class="notice notice-warning"><p><strong>Bali Paradise Surf Lesson:</strong></p><ul style="list-style:disc;margin-left:1.5em">';
		foreach ( $todo as $item ) {
			echo '<li>' . wp_kses_post( $item ) . '</li>';
		}
		echo '</ul></div>';
	}
);
