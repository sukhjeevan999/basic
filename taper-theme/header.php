<?php
/**
 * The header for our theme.
 *
 * @package TaperTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0d7a5f">
	<?php if ( is_front_page() ) : ?>
	<meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : __( 'Build a free, personalized taper-down reduction schedule and track your progress toward zero.', 'tapertheme' ) ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#tt-main"><?php esc_html_e( 'Skip to content', 'tapertheme' ); ?></a>

<header class="tt-site-header" id="tt-site-header">
	<div class="tt-container tt-header-inner">
		<a class="tt-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="tt-logo-mark" aria-hidden="true">T</span>
				<span><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<button type="button" class="tt-nav-toggle" aria-controls="tt-primary-nav" aria-expanded="false">
			<span class="tt-visually-hidden"><?php esc_html_e( 'Menu', 'tapertheme' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<nav class="tt-primary-nav" id="tt-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'tapertheme' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'          => 1,
					)
				);
			} else {
				tapertheme_fallback_primary_menu();
			}
			?>
		</nav>
	</div>
</header>

<main id="tt-main">
