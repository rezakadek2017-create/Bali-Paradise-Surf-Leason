<?php
/**
 * Hardening that is safe to ship in a theme.
 * (File-edit lock and login limits are documented in README.md.)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Hide version fingerprints.
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
add_filter( 'the_generator', '__return_empty_string' );

// XML-RPC is a common brute-force target and is not needed here.
add_filter( 'xmlrpc_enabled', '__return_false' );

// Security headers.
add_action(
	'send_headers',
	function () {
		if ( is_admin() ) {
			return;
		}
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-Frame-Options: SAMEORIGIN' );
		header( 'Referrer-Policy: strict-origin-when-cross-origin' );
		header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()' );
	}
);

// Block username enumeration (?author=1 and /wp-json/wp/v2/users for visitors).
add_action(
	'template_redirect',
	function () {
		if ( ! is_admin() && isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);
add_filter(
	'rest_endpoints',
	function ( $endpoints ) {
		if ( ! is_user_logged_in() ) {
			unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);

// Generic login errors (do not reveal whether a username exists).
add_filter(
	'login_errors',
	function () {
		return 'Login details are incorrect.';
	}
);
