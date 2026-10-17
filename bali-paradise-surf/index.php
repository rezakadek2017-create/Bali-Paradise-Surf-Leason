<?php
/**
 * Fallback template (blog index, archives).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="wrap prose">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
			</article>
		<?php endwhile; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<h1>Nothing here yet</h1>
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to the home page</a></p>
	<?php endif; ?>
</div>
<?php
get_footer();
