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
	<script>
	// Runs before CSS paints so there's no flash of the wrong theme.
	// Respects a saved choice first, then the OS/browser light-dark setting.
	( function () {
		try {
			var saved = localStorage.getItem( 'vd-theme' );
			var theme = saved || ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light' );
			document.documentElement.setAttribute( 'data-vd-theme', theme );
		} catch ( e ) {
			// localStorage can throw in some privacy modes — fall back silently to the default light theme.
		}
	} )();
	</script>
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
				<span>
					<?php bloginfo( 'name' ); ?>
					<?php if ( get_theme_mod( 'vindecoder_header_show_tagline', false ) && get_bloginfo( 'description' ) ) : ?>
						<span class="vd-logo-tagline"><?php bloginfo( 'description' ); ?></span>
					<?php endif; ?>
				</span>
			<?php endif; ?>
		</a>

		<div class="vd-header-actions">
			<button type="button" class="vd-theme-toggle" id="vd-theme-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'vindecodertheme' ); ?>">
				<svg class="vd-theme-icon vd-theme-icon-sun" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="4.5" stroke="currentColor" stroke-width="1.8"/><path stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M12 2.5v2M12 19.5v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2.5 12h2M19.5 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/></svg>
				<svg class="vd-theme-icon vd-theme-icon-moon" width="19" height="19" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M20 14.5A8.5 8.5 0 1 1 9.5 4a7 7 0 0 0 10.5 10.5Z"/></svg>
			</button>

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
							'depth'          => 2,
						)
					);
				} else {
					vindecoder_fallback_primary_menu();
				}
				?>
			</nav>
		</div>
	</div>
</header>

<main id="vd-main">
