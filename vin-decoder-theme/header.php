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
	// Google Consent Mode — must run before any analytics/ads tag (e.g. Site
	// Kit's gtag.js) so those tags pick up these defaults from the start.
	// Everything starts denied; our cookie banner below is what grants it.
	// If the visitor already chose "Accept" on a previous visit, we restore
	// that immediately too so returning visitors aren't reset to denied.
	window.dataLayer = window.dataLayer || [];
	function gtag(){ dataLayer.push(arguments); }
	gtag( 'consent', 'default', {
		'ad_storage': 'denied',
		'ad_user_data': 'denied',
		'ad_personalization': 'denied',
		'analytics_storage': 'denied'
	} );
	try {
		if ( localStorage.getItem( 'vd-cookie-consent' ) === 'accepted' ) {
			gtag( 'consent', 'update', {
				'ad_storage': 'granted',
				'ad_user_data': 'granted',
				'ad_personalization': 'granted',
				'analytics_storage': 'granted'
			} );
		}
	} catch ( e ) {
		// localStorage can throw in some privacy modes — default (denied) stands.
	}
	</script>
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/favicon-32.png?v=' . VINDECODER_VERSION ); ?>" sizes="32x32">
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/favicon-16.png?v=' . VINDECODER_VERSION ); ?>" sizes="16x16">
	<link rel="apple-touch-icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/img/icon-192.png?v=' . VINDECODER_VERSION ); ?>">
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
	<?php
	$vindecoder_og_title = $vindecoder_brand ? $vindecoder_brand['title'] : wp_get_document_title();
	$vindecoder_og_desc  = $vindecoder_brand
		? $vindecoder_brand['meta_description']
		: ( is_singular() && has_excerpt() ? get_the_excerpt() : get_bloginfo( 'description' ) );
	if ( ! $vindecoder_og_desc ) {
		$vindecoder_og_desc = __( 'Free VIN decoder tools for 25 major car and motorcycle brands, powered by the official NHTSA vehicle database.', 'vindecodertheme' );
	}
	?>
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="<?php bloginfo( 'name' ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $vindecoder_og_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( wp_strip_all_tags( $vindecoder_og_desc ) ); ?>">
	<?php
	global $wp;
	$vindecoder_og_url = is_singular() ? get_permalink() : home_url( $wp->request );
	?>
	<meta property="og:url" content="<?php echo esc_url( $vindecoder_og_url ); ?>">
	<meta property="og:image" content="<?php echo esc_url( get_template_directory_uri() . '/assets/img/social-share-banner.png' ); ?>">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $vindecoder_og_title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( wp_strip_all_tags( $vindecoder_og_desc ) ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( get_template_directory_uri() . '/assets/img/social-share-banner.png' ); ?>">
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
				<span class="vd-logo-mark" aria-hidden="true">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-mark.svg' ); ?>" alt="" width="34" height="34" loading="eager">
				</span>
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
