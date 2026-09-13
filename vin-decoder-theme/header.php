<?php
/**
 * The header for our theme.
 *
 * @package VinDecoderTheme
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
	<meta name="theme-color" content="#1d4ed8">
	<?php
	$vindecoder_brand = function_exists( 'vindecoder_get_current_brand' ) ? vindecoder_get_current_brand() : null;
	if ( $vindecoder_brand ) :
		?>
	<meta name="description" content="<?php echo esc_attr( $vindecoder_brand['meta_description'] ); ?>">
	<?php elseif ( is_front_page() ) : ?>
	<meta name="description" content="<?php echo esc_attr__( 'Free VIN decoder tools for 25 major car and motorcycle brands — BMW, Mercedes-Benz, Toyota, Ford, Harley-Davidson, and more. Enter your 17-character VIN to instantly see model, year, engine, and factory build details, for buyers across the US, UK, Canada, and Australia.', 'vindecodertheme' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#vd-main"><?php esc_html_e( 'Skip to content', 'vindecodertheme' ); ?></a>

<header class="vd-site-header" id="vd-site-header">
	<div class="vd-container vd-header-inner">
		<a class="vd-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="vd-logo-mark" aria-hidden="true">VIN</span>
				<span><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</a>

		<button type="button" class="vd-nav-toggle" id="vd-nav-toggle" aria-controls="vd-primary-nav" aria-expanded="false">
			<span class="vd-visually-hidden"><?php esc_html_e( 'Menu', 'vindecodertheme' ); ?></span>
			<span></span><span></span><span></span>
		</button>

		<nav class="vd-primary-nav" id="vd-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'vindecodertheme' ); ?>">
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
				vindecoder_fallback_primary_menu();
			}
			?>
		</nav>
	</div>
</header>

<main id="vd-main">
