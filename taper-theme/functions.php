<?php
/**
 * TaperTheme functions and definitions.
 *
 * @package TaperTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TAPERTHEME_VERSION', '1.0.0' );

/**
 * ==========================================================================
 * 1. THEME SETUP
 * ==========================================================================
 */
function tapertheme_setup() {
	// Translation ready.
	load_theme_textdomain( 'tapertheme', get_template_directory() . '/languages' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// RSS feed links.
	add_theme_support( 'automatic-feed-links' );

	// Post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// HTML5 markup for search form, comment form, gallery, etc.
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	// Selective refresh for widgets in the customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Wide/full alignment support for block editor content.
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// Register navigation menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'tapertheme' ),
			'footer'  => __( 'Footer Navigation', 'tapertheme' ),
		)
	);

	// Custom image sizes used across the theme.
	add_image_size( 'tapertheme-card', 480, 320, true );
	add_image_size( 'tapertheme-hero', 1200, 630, true );
}
add_action( 'after_setup_theme', 'tapertheme_setup' );

/**
 * Set default content width for embeds/images.
 */
function tapertheme_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'tapertheme_content_width', 760 );
}
add_action( 'after_setup_theme', 'tapertheme_content_width', 0 );

/**
 * ==========================================================================
 * 2. ASSET ENQUEUES
 * ==========================================================================
 */
function tapertheme_scripts() {
	// Main stylesheet (contains the entire design system — no build step required).
	wp_enqueue_style( 'tapertheme-style', get_stylesheet_uri(), array(), TAPERTHEME_VERSION );

	// Lightweight mobile nav toggle script (footer-loaded, no dependencies).
	wp_enqueue_script( 'tapertheme-nav', get_template_directory_uri() . '/assets/js/nav.js', array(), TAPERTHEME_VERSION, true );

	// Threaded comment reply script only where needed.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Homepage-only tool scripts to keep every other page ultra-light.
	if ( is_front_page() ) {
		wp_enqueue_script( 'tapertheme-taper-logic', get_template_directory_uri() . '/assets/js/taper-logic.js', array(), TAPERTHEME_VERSION, true );

		// Third-party libraries for client-side PDF/image export, loaded only on the tool page.
		wp_enqueue_script( 'html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', array(), '1.4.1', true );
		wp_enqueue_script( 'jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', array(), '2.5.1', true );

		wp_enqueue_script(
			'tapertheme-pdf-export',
			get_template_directory_uri() . '/assets/js/pdf-export.js',
			array( 'tapertheme-taper-logic', 'html2canvas', 'jspdf' ),
			TAPERTHEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'tapertheme_scripts' );

/**
 * ==========================================================================
 * 3. PERFORMANCE / HEAD CLEANUP
 * ==========================================================================
 * Strip out unused WordPress head cruft to keep the front end ultra-fast.
 */
function tapertheme_cleanup_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Disable emoji scripts/styles for a lighter payload.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'tapertheme_cleanup_head' );

/**
 * Remove the emoji DNS-prefetch entry.
 */
function tapertheme_disable_emoji_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls = array_diff( $urls, array( '//s.w.org' ) );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'tapertheme_disable_emoji_dns_prefetch', 10, 2 );

/**
 * Remove query strings from static resource URLs to improve cache hit rates
 * on CDNs/proxies (skips admin so cache-busting still works while developing).
 */
function tapertheme_remove_script_style_version( $src ) {
	if ( is_admin() || empty( $src ) ) {
		return $src;
	}
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}
	return $src;
}
add_filter( 'script_loader_src', 'tapertheme_remove_script_style_version', 15, 1 );
add_filter( 'style_loader_src', 'tapertheme_remove_script_style_version', 15, 1 );

/**
 * Preconnect to the CDN used for the PDF export libraries so they load fast
 * when the homepage tool is used.
 */
function tapertheme_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type && is_front_page() ) {
		$hints[] = array(
			'href' => 'https://cdnjs.cloudflare.com',
			'crossorigin',
		);
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'tapertheme_resource_hints', 10, 2 );

/**
 * ==========================================================================
 * 4. WIDGET AREAS
 * ==========================================================================
 */
function tapertheme_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'tapertheme' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Appears on blog archive and single post pages.', 'tapertheme' ),
			'before_widget' => '<div class="tt-widget"><div class="tt-card">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3 class="tt-widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'tapertheme_widgets_init' );

/**
 * ==========================================================================
 * 5. TEMPLATE HELPERS
 * ==========================================================================
 */

/**
 * Fallback menu output when no menu has been assigned to a location yet,
 * so the theme never errors out on a fresh install.
 */
function tapertheme_fallback_primary_menu() {
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'tapertheme' ) . '</a></li>';

	$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( $privacy_id ) {
		echo '<li><a href="' . esc_url( get_permalink( $privacy_id ) ) . '">' . esc_html__( 'Privacy Policy', 'tapertheme' ) . '</a></li>';
	}
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Contact', 'tapertheme' ) . '</a></li>';
	echo '</ul>';
}

/**
 * Excerpt length/more string tuned for the blog archive cards.
 */
function tapertheme_excerpt_length( $length ) {
	return is_admin() ? $length : 30;
}
add_filter( 'excerpt_length', 'tapertheme_excerpt_length' );

function tapertheme_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'tapertheme_excerpt_more' );

/**
 * ==========================================================================
 * 6. SECURITY / MISC HARDENING
 * ==========================================================================
 */
// Hide WordPress version from RSS and other generators.
add_filter( 'the_generator', '__return_empty_string' );

// Disable the XML-RPC endpoint (not needed by this theme, reduces attack surface).
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Register the block-editor color palette to match the theme design tokens
 * (used only if the Block/Gutenberg editor is active on other content).
 */
function tapertheme_editor_color_palette() {
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Primary Green', 'tapertheme' ),
				'slug'  => 'primary',
				'color' => '#0d7a5f',
			),
			array(
				'name'  => __( 'Ink', 'tapertheme' ),
				'slug'  => 'ink',
				'color' => '#16211f',
			),
			array(
				'name'  => __( 'Surface', 'tapertheme' ),
				'slug'  => 'surface',
				'color' => '#ffffff',
			),
			array(
				'name'  => __( 'Alert Red', 'tapertheme' ),
				'slug'  => 'alert',
				'color' => '#b3261e',
			),
		)
	);
}
add_action( 'after_setup_theme', 'tapertheme_editor_color_palette' );
