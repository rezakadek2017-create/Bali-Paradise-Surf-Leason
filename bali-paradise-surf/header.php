<?php
/**
 * Site header.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$bpsl_nav = array(
	'about'     => 'About',
	'packages'  => 'Packages',
	'locations' => 'Locations',
	'gallery'   => 'Gallery',
	'reviews'   => 'Reviews',
	'faq'       => 'FAQ',
);
// Navigation items remain available by default
$bpsl_solid = is_front_page() ? '' : ' site-header--solid';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0B4A52">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header<?php echo esc_attr( $bpsl_solid ); ?>" id="top">
	<div class="progress" aria-hidden="true"><span></span></div>
	<div class="wrap site-header__bar">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Bali Paradise Surf Lesson, home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="brand__mark"><?php echo bpsl_icon( 'wave', 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="brand__text">Bali Paradise<small>Surf Lesson</small></span>
			<?php endif; ?>
		</a>

		<nav class="nav" id="site-nav" aria-label="Primary">
			<ul>
				<?php foreach ( $bpsl_nav as $id => $label ) : ?>
					<li><a href="<?php echo esc_url( bpsl_anchor( $id ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
				<?php endforeach; ?>
				<li class="nav__cta"><a class="btn btn--sun btn--sm" href="<?php echo esc_url( bpsl_anchor( 'book' ) ); ?>">Book a lesson</a></li>
			</ul>
		</nav>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
			<span class="nav-toggle__open"><?php echo bpsl_icon( 'menu', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="nav-toggle__close"><?php echo bpsl_icon( 'close', 26 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
			<span class="screen-reader-text">Menu</span>
		</button>
	</div>
</header>

<main id="main" class="<?php echo is_front_page() ? 'front-main' : 'page-main'; ?>">
