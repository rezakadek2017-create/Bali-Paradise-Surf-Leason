<?php
/**
 * 404 page.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="wrap prose prose--center">
	<h1>This page drifted out to sea</h1>
	<p>The page you are looking for does not exist. Head back to shore and start from the home page.</p>
	<p><a class="btn btn--sun" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to home</a></p>
</div>
<?php
get_footer();
