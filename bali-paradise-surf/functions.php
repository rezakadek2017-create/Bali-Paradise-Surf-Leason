<?php
/**
 * Bali Paradise Surf Lesson - theme bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BPSL_VERSION', '1.0.0' );
define( 'BPSL_DIR', get_template_directory() );
define( 'BPSL_URI', get_template_directory_uri() );

require_once BPSL_DIR . '/inc/data.php';
require_once BPSL_DIR . '/inc/icons.php';
require_once BPSL_DIR . '/inc/setup.php';
require_once BPSL_DIR . '/inc/post-types.php';
require_once BPSL_DIR . '/inc/customizer.php';
require_once BPSL_DIR . '/inc/seo.php';
require_once BPSL_DIR . '/inc/security.php';
require_once BPSL_DIR . '/inc/seed.php';
