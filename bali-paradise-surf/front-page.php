<?php
/**
 * Landing page. Content is edited in the Customizer and under the
 * Packages / Locations / Gallery / Testimonials / FAQs menus.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

$parts = array( 'hero', 'about', 'packages', 'included', 'process', 'locations', 'gallery', 'videos', 'reviews', 'faq', 'booking' );
foreach ( $parts as $part ) {
	get_template_part( 'template-parts/' . $part );
}

get_footer();
