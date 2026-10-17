<?php
/**
 * Inline SVG icons (stroke style, no external requests).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bpsl_icon( $name, $size = 22 ) {
	$paths = array(
		'whatsapp'  => '<path d="M20 11.5a8.5 8.5 0 0 1-12.6 7.4L3 20l1.2-4.2A8.5 8.5 0 1 1 20 11.5z"/><path d="M9 8.6c.3 2.5 2.8 5 5.4 5.4l1.3-1.3-2-1-.9.7c-.8-.3-1.7-1.2-2-2l.7-.9-1-2z"/>',
		'check'     => '<path d="M4 12.5l5 5L20 6.5"/>',
		'shield'    => '<path d="M12 3l8 3v6c0 4.5-3.2 7.6-8 9-4.8-1.4-8-4.5-8-9V6l8-3z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>',
		'heart'     => '<path d="M12 20s-7.5-4.6-7.5-10A4.3 4.3 0 0 1 12 7.4 4.3 4.3 0 0 1 19.5 10C19.5 15.4 12 20 12 20z"/>',
		'users'     => '<circle cx="9" cy="8" r="3.2"/><path d="M3 20c.4-3.4 2.8-5.4 6-5.4s5.6 2 6 5.4"/><circle cx="17" cy="9" r="2.4"/><path d="M16.5 14.4c2.4.2 4 1.8 4.5 4.6"/>',
		'wave'      => '<path d="M2 15c2.5 0 2.5-3 5-3s2.5 3 5 3 2.5-3 5-3 2.5 3 5 3"/><path d="M2 20c2.5 0 2.5-3 5-3s2.5 3 5 3 2.5-3 5-3 2.5 3 5 3"/><path d="M5 9c1.5-3.5 5-5.5 9-5-2 1-3 2.6-3 4.5"/>',
		'flower'    => '<circle cx="12" cy="12" r="2.4"/><path d="M12 9.6C10.4 7 10.4 4.6 12 3c1.6 1.6 1.6 4 0 6.6zM14.4 12c2.6-1.6 5-1.6 6.6 0-1.6 1.6-4 1.6-6.6 0zM12 14.4c1.6 2.6 1.6 5 0 6.6-1.6-1.6-1.6-4 0-6.6zM9.6 12C7 13.6 4.6 13.6 3 12c1.6-1.6 4-1.6 6.6 0z"/>',
		'pin'       => '<path d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11z"/><circle cx="12" cy="10" r="2.3"/>',
		'star'      => '<path d="M12 3.5l2.6 5.5 6 .8-4.4 4.2 1.1 6-5.3-2.9-5.3 2.9 1.1-6L3.4 9.8l6-.8L12 3.5z"/>',
		'instagram' => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r=".8"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="icon icon--%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$paths[ $name ] // phpcs:ignore WordPress.Security.EscapeOutput -- static trusted markup.
	);
}
