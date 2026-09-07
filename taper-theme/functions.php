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

/**
 * ==========================================================================
 * 7. DEFAULT CONTENT & MENUS ON ACTIVATION
 * ==========================================================================
 * On first activation, provision the legal pages the theme links to
 * (Medical Disclaimer, Terms of Service, Privacy Policy, Contact), register
 * the Privacy Policy page with WordPress core, and build the Primary/Footer
 * menus so the theme is fully functional with zero manual setup. Existing
 * pages/menus are never overwritten.
 */

/**
 * Default page content, keyed by slug. Site owners should review and adapt
 * this copy (especially the [Your Company Name] / contact placeholders)
 * before relying on it as their actual published legal text.
 */
function tapertheme_get_default_pages() {
	$today = esc_html( date_i18n( get_option( 'date_format' ) ) );

	return array(
		'medical-disclaimer' => array(
			'title'   => __( 'Medical Disclaimer', 'tapertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>

<h2>General Information Only</h2>
<p>The reduction schedule tool, calculators, articles, and any other content on this website (\"the Site\") are provided for general educational and self-organizational planning purposes only. Nothing on this Site constitutes, and should not be relied upon as, medical advice, diagnosis, or treatment.</p>

<h2>No Doctor-Patient Relationship</h2>
<p>Using this Site, generating a schedule, or reading any content here does not create a doctor-patient, therapist-client, or any other professional healthcare relationship between you and the Site owner, its authors, or contributors. We are not your treatment provider.</p>

<h2>Not a Substitute for Professional Care</h2>
<p>Always seek the advice of your physician, addiction medicine specialist, or other qualified health provider with any questions you may have regarding a medical condition, substance dependency, or before starting, changing, or stopping any pattern of use. Never disregard professional medical advice or delay seeking it because of something you read or generated on this Site.</p>

<h2>Heavy Alcohol or Chemical Dependency Requires Medical Supervision</h2>
<p><strong>If you have a heavy or long-term alcohol dependency, or a dependency on benzodiazepines, opioids, or any other chemical substance, do not attempt to reduce or stop use on your own without first speaking to a doctor.</strong> Abrupt or unsupervised reduction of alcohol or certain sedative-type substances can trigger severe, potentially life-threatening withdrawal symptoms, including tremors, hallucinations, seizures, and delirium tremens. A physician or addiction medicine specialist can assess whether a medically supervised taper or inpatient/outpatient detoxification program is necessary for your safety, and this Site's calculator is not a substitute for that assessment.</p>

<h2>When to Seek Emergency Care</h2>
<p>If you or someone you are supporting experiences confusion, hallucinations, seizures, severe tremor, chest pain, difficulty breathing, or thoughts of self-harm at any point during a reduction attempt, stop what you are doing and seek emergency medical attention immediately (in the US, call 911 or go to the nearest emergency room; outside the US, contact your local emergency number).</p>

<h2>Assumption of Risk</h2>
<p>By using this Site's tools and schedules, you acknowledge that any reduction plan you follow is undertaken at your own discretion and risk, and that the Site owner is not responsible for any outcome, adverse reaction, or withdrawal event that results from your use of the information or tools provided here. See our <a href=\"" . esc_url( home_url( '/terms-of-service/' ) ) . "\">Terms of Service</a> for the full limitation of liability that applies to your use of this Site.</p>

<h2>Contact</h2>
<p>Questions about this disclaimer can be sent via our <a href=\"" . esc_url( home_url( '/contact/' ) ) . "\">Contact page</a>.</p>",
		),
		'terms-of-service'   => array(
			'title'   => __( 'Terms of Service', 'tapertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>

<h2>1. Acceptance of Terms</h2>
<p>By accessing or using this website (\"the Site\"), you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use the Site.</p>

<h2>2. Description of Service</h2>
<p>The Site provides a free, self-directed reduction-schedule planning tool and related educational content. All schedule calculations are performed entirely within your own web browser (client-side); no health, consumption, or cost data you enter into the calculator is transmitted to or stored on our servers. Optional progress tracking is saved only in your browser's local storage, on your own device.</p>

<h2>3. Not Medical Advice</h2>
<p>The Site is an educational and organizational tool, not a medical device or medical service. Please review our <a href=\"" . esc_url( home_url( '/medical-disclaimer/' ) ) . "\">Medical Disclaimer</a> for important information about the limits of this tool, particularly regarding alcohol or chemical dependency.</p>

<h2>4. User Responsibilities</h2>
<p>You are solely responsible for the accuracy of the information you enter into the calculator and for any decisions you make based on its output. You agree to use the Site only for lawful purposes and in a way that does not infringe the rights of, or restrict or inhibit the use and enjoyment of the Site by, any third party.</p>

<h2>5. Intellectual Property</h2>
<p>The design, text, graphics, and underlying code of the Site are owned by the Site operator or its licensors and are protected by applicable intellectual property laws, except where otherwise noted. You may not reproduce, distribute, or create derivative works from this content without prior written permission.</p>

<h2>6. Third-Party Links and Advertising</h2>
<p>The Site may display advertisements served by third-party advertising networks (such as Google AdSense), which may use cookies and similar technologies to serve relevant ads. The Site may also link to third-party websites. We are not responsible for the content, accuracy, or privacy practices of any linked third-party site or advertiser.</p>

<h2>7. No Warranty</h2>
<p>The Site and its tools are provided \"as is\" and \"as available,\" without warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, accuracy, or non-infringement. We do not warrant that the calculator's output is error-free or suitable for your particular circumstances.</p>

<h2>8. Limitation of Liability</h2>
<p>To the fullest extent permitted by law, the Site operator, its authors, and contributors shall not be liable for any direct, indirect, incidental, consequential, or special damages arising out of or in connection with your use of, or inability to use, the Site or its tools, including any outcome related to a reduction schedule you generate or follow.</p>

<h2>9. Changes to These Terms</h2>
<p>We may update these Terms of Service from time to time. The \"Last updated\" date at the top of this page reflects the most recent revision. Continued use of the Site after changes are posted constitutes acceptance of the revised terms.</p>

<h2>10. Contact</h2>
<p>Questions about these Terms can be sent via our <a href=\"" . esc_url( home_url( '/contact/' ) ) . "\">Contact page</a>.</p>",
		),
		'privacy-policy'     => array(
			'title'   => __( 'Privacy Policy', 'tapertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>

<h2>Overview</h2>
<p>This Privacy Policy explains what information this website (\"the Site\") collects and how it is used. We designed the reduction-schedule tool to work entirely in your browser: the substance type, baseline amount, pace, cost, and any progress check-ins you enter are processed on your own device using JavaScript and, if you choose to keep them, are saved only in your browser's local storage. We do not receive, transmit, or store this information on our servers.</p>

<h2>Information Collected Automatically</h2>
<p>Like most websites, our hosting provider and any analytics or advertising services we use may automatically collect standard technical information such as your IP address, browser type, device type, referring pages, and pages visited, typically through server logs and cookies. This is used in aggregate to maintain and improve the Site and is not linked to the personal reduction-schedule data described above, which never leaves your device.</p>

<h2>Cookies and Local Storage</h2>
<p>The Site uses your browser's local storage (not a server-side database) to remember a generated schedule and your daily check-ins between visits, so you don't lose progress when you close the tab. You can clear this at any time via your browser's site data settings. Separately, cookies may be set by advertising or analytics providers as described below.</p>

<h2>Advertising</h2>
<p>This Site may display ads served by third-party vendors, including Google, which may use cookies (such as the DoubleClick cookie) to serve ads based on your prior visits to this or other websites. You may opt out of personalized advertising by visiting Google's Ads Settings, or opt out of third-party vendor use of cookies for personalized advertising by visiting <a href=\"https://www.aboutads.info/choices/\" rel=\"nofollow noopener\" target=\"_blank\">www.aboutads.info</a>.</p>

<h2>Children's Privacy</h2>
<p>This Site is not directed to children under 13, and we do not knowingly collect personal information from children under 13.</p>

<h2>Data Retention and Your Choices</h2>
<p>Because your schedule and check-in data live only in your browser's local storage, you are always in control of it: clearing your browser's site data, using a private/incognito window, or using a different device or browser will remove or exclude it. We have no server-side copy to delete on your behalf, because none is created.</p>

<h2>Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. The \"Last updated\" date at the top of this page reflects the most recent revision.</p>

<h2>Contact</h2>
<p>Questions about this Privacy Policy can be sent via our <a href=\"" . esc_url( home_url( '/contact/' ) ) . "\">Contact page</a>.</p>",
		),
		'contact'            => array(
			'title'   => __( 'Contact', 'tapertheme' ),
			'content' => '<p>' . esc_html__( "Have a question about the reduction schedule tool, found a bug, or need to reach us about this website? We'd like to hear from you.", 'tapertheme' ) . '</p>
<p>' . esc_html__( 'Email us at:', 'tapertheme' ) . ' <a href="mailto:support@example.com">support@example.com</a></p>
<p><em>' . esc_html__( 'Please replace this placeholder address with your own support email before launching your site.', 'tapertheme' ) . '</em></p>
<p>' . esc_html__( 'If you are experiencing a medical emergency, please do not use this contact form — call your local emergency number immediately.', 'tapertheme' ) . '</p>',
		),
	);
}

/**
 * Create the default pages (if they do not already exist by slug), register
 * the Privacy Policy page with WordPress core, and provision the Primary
 * and Footer navigation menus.
 */
function tapertheme_provision_default_pages_and_menus() {
	$page_ids = array();

	foreach ( tapertheme_get_default_pages() as $slug => $page ) {
		$existing = get_page_by_path( $slug );

		if ( $existing instanceof WP_Post ) {
			$page_ids[ $slug ] = $existing->ID;
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $page['title'],
				'post_name'    => $slug,
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			),
			true
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$page_ids[ $slug ] = $post_id;
		}
	}

	// Register the Privacy Policy page with WordPress core (Settings > Privacy).
	if ( ! empty( $page_ids['privacy-policy'] ) && ! get_option( 'wp_page_for_privacy_policy' ) ) {
		update_option( 'wp_page_for_privacy_policy', $page_ids['privacy-policy'] );
	}

	tapertheme_provision_menus( $page_ids );
}
add_action( 'after_switch_theme', 'tapertheme_provision_default_pages_and_menus' );

/**
 * Build the Primary (Home, Contact) and Footer (Privacy Policy, Terms,
 * Medical Disclaimer) menus and assign them to their theme locations,
 * but only if those locations aren't already assigned a menu.
 */
function tapertheme_provision_menus( $page_ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	if ( ! has_nav_menu( 'primary' ) ) {
		$menu_id = wp_create_nav_menu( __( 'Primary Menu', 'tapertheme' ) );

		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => __( 'Home', 'tapertheme' ),
					'menu-item-url'    => home_url( '/' ),
					'menu-item-status' => 'publish',
				)
			);

			if ( ! empty( $page_ids['contact'] ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $page_ids['contact'],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}

			$locations['primary'] = $menu_id;
		}
	}

	if ( ! has_nav_menu( 'footer' ) ) {
		$menu_id = wp_create_nav_menu( __( 'Footer Menu', 'tapertheme' ) );

		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( array( 'privacy-policy', 'terms-of-service', 'medical-disclaimer' ) as $slug ) {
				if ( empty( $page_ids[ $slug ] ) ) {
					continue;
				}
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-object-id' => $page_ids[ $slug ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}

			$locations['footer'] = $menu_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
