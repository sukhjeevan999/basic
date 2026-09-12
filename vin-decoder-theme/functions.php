<?php
/**
 * VinDecoderTheme functions and definitions.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'VINDECODER_VERSION', '1.0.0' );

/**
 * ==========================================================================
 * 1. BRAND REGISTRY
 * ==========================================================================
 * Single source of truth for the 5 supported brands. Each brand gets its
 * own dedicated page (assigned the "VIN Decoder — Brand" template) at its
 * own exact-match-keyword URL, and is also listed as a selectable card on
 * the homepage hub. Add a brand here and create one page for it — no other
 * code changes needed.
 */
function vindecoder_get_brands() {
	return array(
		'bmw'         => array(
			'label'             => 'BMW',
			'title'             => 'BMW VIN Decoder',
			'tagline'           => 'Decode any BMW VIN for free — model, chassis era, engine, and factory build details.',
			'meta_description'  => 'Free BMW VIN decoder. Enter your 17-character VIN to instantly reveal your BMW\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WBA5A5C50FD509130',
			'intro'             => 'BMW build sheets are notoriously hard to track down once a car leaves the factory. A VIN decode won\'t recover the full options list a dealer build sheet has, but it instantly confirms the details that matter most when buying, selling, or researching a BMW: the exact model, model year, engine, and body style encoded into the VIN itself.',
		),
		'audi'        => array(
			'label'             => 'Audi',
			'title'             => 'Audi VIN Decoder',
			'tagline'           => 'Decode any Audi VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Audi VIN decoder. Enter your 17-character VIN to instantly reveal your Audi\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WAUZZZ4GZDN018595',
			'intro'             => 'Whether you\'re buying a used Audi, verifying a listing, or just curious what a partial VIN glimpsed in a photo actually decodes to, a quick VIN check confirms the model, model year, engine, and body style the factory encoded into the car — without needing a dealer login or a paid report.',
		),
		'porsche'     => array(
			'label'             => 'Porsche',
			'title'             => 'Porsche VIN Decoder',
			'tagline'           => 'Decode any Porsche VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Porsche VIN decoder. Enter your 17-character VIN to instantly reveal your Porsche\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WP0AA2A99JS156123',
			'intro'             => 'Porsche VINs pack in more than most buyers realize — model line, model year, and body style are all encoded directly into the sequence. Before you rely on a seller\'s description alone, a VIN decode gives you an independent, factory-encoded read on what the car actually is.',
		),
		'lexus'       => array(
			'label'             => 'Lexus',
			'title'             => 'Lexus VIN Decoder',
			'tagline'           => 'Decode any Lexus VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Lexus VIN decoder. Enter your 17-character VIN to instantly reveal your Lexus\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'JTHBK1EG8E2123456',
			'intro'             => 'A Lexus VIN encodes its model, model year, engine, and body style at the factory — details worth confirming independently before you buy, sell, or insure the car, rather than relying only on a listing description.',
		),
		'range-rover' => array(
			'label'             => 'Range Rover',
			'nhtsa_make'        => 'Land Rover',
			'title'             => 'Range Rover VIN Decoder',
			'tagline'           => 'Decode any Range Rover VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Range Rover VIN decoder. Enter your 17-character VIN to instantly reveal your Range Rover\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'SALGS2SE6JA123456',
			'intro'             => 'Range Rover model lines (Evoque, Velar, Sport, and the full-size Range Rover) share design language but differ enormously in engine options and factory specification. A VIN decode confirms exactly which one you\'re looking at, straight from the encoded build data.',
		),
	);
}

/**
 * Look up a single brand's config by slug. Returns null if unknown.
 */
function vindecoder_get_brand( $slug ) {
	$brands = vindecoder_get_brands();
	return isset( $brands[ $slug ] ) ? $brands[ $slug ] : null;
}

/**
 * Determine which brand a given page represents. We key off the page slug
 * matching a brand slug exactly, so setting up a brand page is just:
 * create a Page with slug "bmw" (or "range-rover", etc.), assign it the
 * "VIN Decoder — Brand Page" template, publish.
 */
function vindecoder_get_current_brand() {
	global $post;
	if ( ! $post ) {
		return null;
	}
	return vindecoder_get_brand( $post->post_name );
}

/**
 * ==========================================================================
 * 2. THEME SETUP
 * ==========================================================================
 */
function vindecoder_setup() {
	load_theme_textdomain( 'vindecodertheme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 280,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'vindecodertheme' ),
			'footer'  => __( 'Footer Navigation', 'vindecodertheme' ),
		)
	);

	add_image_size( 'vindecoder-card', 480, 320, true );
}
add_action( 'after_setup_theme', 'vindecoder_setup' );

function vindecoder_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'vindecoder_content_width', 760 );
}
add_action( 'after_setup_theme', 'vindecoder_content_width', 0 );

/**
 * Register the shared brand-decoder page template so it's selectable from
 * the block/classic editor's "Page Attributes > Template" dropdown.
 */
function vindecoder_register_page_templates( $templates ) {
	$templates['template-vin-decoder.php'] = __( 'VIN Decoder — Brand Page', 'vindecodertheme' );
	return $templates;
}
add_filter( 'theme_page_templates', 'vindecoder_register_page_templates' );

/**
 * ==========================================================================
 * 3. ASSET ENQUEUES
 * ==========================================================================
 */
function vindecoder_scripts() {
	wp_enqueue_style( 'vindecoder-google-fonts', 'https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&display=swap', array(), null );
	wp_enqueue_style( 'vindecoder-style', get_stylesheet_uri(), array( 'vindecoder-google-fonts' ), VINDECODER_VERSION );

	wp_enqueue_script( 'vindecoder-nav', get_template_directory_uri() . '/assets/js/nav.js', array(), VINDECODER_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Decoder + PDF export only load on an actual brand decoder page.
	if ( is_page_template( 'template-vin-decoder.php' ) ) {
		wp_enqueue_script(
			'vindecoder-tool',
			get_template_directory_uri() . '/assets/js/vin-decoder.js',
			array(),
			VINDECODER_VERSION,
			true
		);

		wp_localize_script(
			'vindecoder-tool',
			'VinDecoderConfig',
			array(
				'restUrl'      => esc_url_raw( rest_url( 'vindecoder/v1/decode' ) ),
				'nonce'        => wp_create_nonce( 'wp_rest' ),
				'expectedMake' => vindecoder_get_current_brand()
					? ( vindecoder_get_current_brand()['nhtsa_make'] ?? vindecoder_get_current_brand()['label'] )
					: '',
				'brandLabel'   => vindecoder_get_current_brand() ? vindecoder_get_current_brand()['label'] : '',
			)
		);

		wp_enqueue_script( 'html2canvas', 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js', array(), '1.4.1', true );
		wp_enqueue_script( 'jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js', array(), '2.5.1', true );

		wp_enqueue_script(
			'vindecoder-pdf-export',
			get_template_directory_uri() . '/assets/js/pdf-export.js',
			array( 'vindecoder-tool', 'html2canvas', 'jspdf' ),
			VINDECODER_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'vindecoder_scripts' );

/**
 * ==========================================================================
 * 4. PERFORMANCE / HEAD CLEANUP
 * ==========================================================================
 */
function vindecoder_cleanup_head() {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'vindecoder_cleanup_head' );

function vindecoder_resource_hints( $hints, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$hints[] = array( 'href' => 'https://fonts.googleapis.com' );
		$hints[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		if ( is_page_template( 'template-vin-decoder.php' ) ) {
			$hints[] = array( 'href' => 'https://cdnjs.cloudflare.com', 'crossorigin' );
			$hints[] = array( 'href' => 'https://vpic.nhtsa.gov' );
		}
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'vindecoder_resource_hints', 10, 2 );

/**
 * Exact-match document title per brand page, and a hub title for the
 * homepage.
 */
function vindecoder_document_title_parts( $title_parts ) {
	if ( is_page_template( 'template-vin-decoder.php' ) ) {
		$brand = vindecoder_get_current_brand();
		if ( $brand ) {
			$title_parts['title'] = $brand['title'];
		}
	} elseif ( is_front_page() && ! is_paged() ) {
		$title_parts['title'] = __( 'Free VIN Decoder — BMW, Audi, Porsche, Lexus & Range Rover', 'vindecodertheme' );
	}
	return $title_parts;
}
add_filter( 'document_title_parts', 'vindecoder_document_title_parts' );

/**
 * ==========================================================================
 * 5. REST API — SERVER-SIDE NHTSA PROXY
 * ==========================================================================
 * The browser calls this same-origin endpoint instead of the NHTSA vPIC API
 * directly, which avoids any CORS issues and lets us validate/sanitize the
 * VIN server-side before forwarding the request. No API key, no cost — the
 * NHTSA vPIC API is a free public service of the U.S. Dept. of Transportation.
 */
function vindecoder_register_rest_routes() {
	register_rest_route(
		'vindecoder/v1',
		'/decode',
		array(
			'methods'             => 'GET',
			'callback'            => 'vindecoder_handle_decode_request',
			'permission_callback' => '__return_true',
			'args'                => array(
				'vin' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'vindecoder_register_rest_routes' );

function vindecoder_handle_decode_request( $request ) {
	$vin = strtoupper( trim( (string) $request->get_param( 'vin' ) ) );

	// VIN standard: exactly 17 characters, excludes I, O, Q to avoid
	// confusion with 1 and 0.
	if ( ! preg_match( '/^[A-HJ-NPR-Z0-9]{17}$/', $vin ) ) {
		return new WP_Error(
			'vindecoder_invalid_vin',
			__( 'That doesn\'t look like a valid 17-character VIN. VINs never contain the letters I, O, or Q.', 'vindecodertheme' ),
			array( 'status' => 400 )
		);
	}

	$transient_key = 'vindecoder_' . md5( $vin );
	$cached        = get_transient( $transient_key );
	if ( false !== $cached ) {
		return rest_ensure_response( $cached );
	}

	// Some shared-hosting servers have a broken/unreliable IPv6 DNS resolver
	// while IPv4 resolution works fine, which surfaces as "cURL error 6:
	// Could not resolve host" even though the host is genuinely reachable.
	// Forcing IPv4 resolution for this specific request is a safe, common
	// fix for that class of host. Only applies while this filter is active
	// (added and removed around the single wp_remote_get call below).
	$vindecoder_force_ipv4 = function ( $handle ) {
		if ( defined( 'CURL_IPRESOLVE_V4' ) ) {
			curl_setopt( $handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
		}
	};
	add_action( 'http_api_curl', $vindecoder_force_ipv4 );

	$api_url  = 'https://vpic.nhtsa.gov/api/vehicles/DecodeVinValuesExtended/' . rawurlencode( $vin ) . '?format=json';
	$response = wp_remote_get(
		$api_url,
		array(
			'timeout' => 12,
			'headers' => array( 'Accept' => 'application/json' ),
		)
	);

	remove_action( 'http_api_curl', $vindecoder_force_ipv4 );

	if ( is_wp_error( $response ) ) {
		// TEMPORARY DIAGNOSTIC: surface the real cURL/WP_Error message so we
		// can see exactly why the server-to-NHTSA request failed (timeout,
		// SSL verification, DNS, connection refused, etc). Remove the
		// "debug_detail" line once the root cause is fixed.
		return new WP_Error(
			'vindecoder_upstream_error',
			__( 'The vehicle database could not be reached right now. Please try again in a moment.', 'vindecodertheme' )
				. ' [debug_detail: ' . $response->get_error_message() . ']',
			array( 'status' => 502 )
		);
	}

	$code = wp_remote_retrieve_response_code( $response );
	if ( 200 !== (int) $code ) {
		return new WP_Error(
			'vindecoder_upstream_status',
			__( 'The vehicle database returned an unexpected response. Please try again.', 'vindecodertheme' ),
			array( 'status' => 502 )
		);
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( empty( $body['Results'][0] ) || ! is_array( $body['Results'][0] ) ) {
		return new WP_Error(
			'vindecoder_no_results',
			__( 'No decode results were returned for that VIN.', 'vindecodertheme' ),
			array( 'status' => 404 )
		);
	}

	$r = $body['Results'][0];

	// Flatten to just the fields the front end needs — keeps the payload
	// small and avoids passing along NHTSA's internal error-code chatter.
	$result = array(
		'vin'          => $vin,
		'make'         => $r['Make'] ?? '',
		'model'        => $r['Model'] ?? '',
		'modelYear'    => $r['ModelYear'] ?? '',
		'series'       => $r['Series'] ?? '',
		'trim'         => $r['Trim'] ?? '',
		'bodyClass'    => $r['BodyClass'] ?? '',
		'engineCyl'    => $r['EngineCylinders'] ?? '',
		'engineDisp'   => $r['DisplacementL'] ?? '',
		'engineHP'     => $r['EngineHP'] ?? '',
		'fuelType'     => $r['FuelTypePrimary'] ?? '',
		'driveType'    => $r['DriveType'] ?? '',
		'transmission' => $r['TransmissionStyle'] ?? '',
		'plantCity'    => $r['PlantCity'] ?? '',
		'plantCountry' => $r['PlantCountry'] ?? '',
		'doors'        => $r['Doors'] ?? '',
		'errorCode'    => $r['ErrorCode'] ?? '',
	);

	// Cache successful decodes for a day — VIN decode results never change,
	// so this cuts repeat-lookup load on the free NHTSA service to zero.
	set_transient( $transient_key, $result, DAY_IN_SECONDS );

	return rest_ensure_response( $result );
}

/**
 * ==========================================================================
 * 6. TEMPLATE HELPERS
 * ==========================================================================
 */
function vindecoder_fallback_primary_menu() {
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'vindecodertheme' ) . '</a></li>';
	foreach ( vindecoder_get_brands() as $slug => $brand ) {
		$page = get_page_by_path( $slug );
		if ( $page instanceof WP_Post ) {
			echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $brand['label'] ) . '</a></li>';
		}
	}
	echo '<li><a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Contact', 'vindecodertheme' ) . '</a></li>';
	echo '</ul>';
}

function vindecoder_excerpt_length( $length ) {
	return is_admin() ? $length : 30;
}
add_filter( 'excerpt_length', 'vindecoder_excerpt_length' );

function vindecoder_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'vindecoder_excerpt_more' );

/**
 * ==========================================================================
 * 7. SECURITY / MISC HARDENING
 * ==========================================================================
 */
add_filter( 'the_generator', '__return_empty_string' );
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * ==========================================================================
 * 8. STRUCTURED DATA (per brand page: WebApplication + FAQPage)
 * ==========================================================================
 */
function vindecoder_brand_schema() {
	if ( ! is_page_template( 'template-vin-decoder.php' ) ) {
		return;
	}
	$brand = vindecoder_get_current_brand();
	if ( ! $brand ) {
		return;
	}

	$schema = array(
		'@context'            => 'https://schema.org',
		'@type'               => 'WebApplication',
		'name'                => $brand['title'],
		'applicationCategory' => 'UtilityApplication',
		'operatingSystem'     => 'Any (runs in a web browser)',
		'url'                 => get_permalink(),
		'description'         => $brand['meta_description'],
		'offers'              => array(
			'@type'         => 'Offer',
			'price'         => '0',
			'priceCurrency' => 'USD',
		),
		'isAccessibleForFree' => true,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'vindecoder_brand_schema' );

/**
 * ==========================================================================
 * 9. AFFILIATE / RESOURCE LINKS (vehicle history reports, etc.)
 * ==========================================================================
 * Same pattern as our other themes: optional, site-owner-controlled links
 * that render nowhere until a URL is actually filled in under Appearance >
 * Customize, with a mandatory disclosure whenever any link is active.
 */
function vindecoder_get_affiliate_slots() {
	return array(
		'history_report' => array(
			'label'       => __( 'Vehicle History Report', 'vindecodertheme' ),
			'description' => __( 'e.g. your Carfax, AutoCheck, or similar affiliate link.', 'vindecodertheme' ),
		),
		'insurance'       => array(
			'label'       => __( 'Insurance Quote Comparison', 'vindecodertheme' ),
			'description' => __( 'A referral link for an auto insurance comparison service.', 'vindecodertheme' ),
		),
		'warranty'        => array(
			'label'       => __( 'Extended Warranty Quote', 'vindecodertheme' ),
			'description' => __( 'A referral link for an extended vehicle warranty provider.', 'vindecodertheme' ),
		),
	);
}

function vindecoder_customize_register_affiliate( $wp_customize ) {
	$wp_customize->add_section(
		'vindecoder_affiliate',
		array(
			'title'       => __( 'Affiliate & Resource Links', 'vindecodertheme' ),
			'description' => __( 'Optional. Leave any field blank to hide it. A disclosure is shown automatically whenever at least one link is set.', 'vindecodertheme' ),
			'priority'    => 131,
		)
	);

	foreach ( vindecoder_get_affiliate_slots() as $key => $slot ) {
		$url_id   = 'vindecoder_affiliate_' . $key . '_url';
		$label_id = 'vindecoder_affiliate_' . $key . '_label';

		$wp_customize->add_setting( $label_id, array( 'default' => $slot['label'], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			$label_id,
			array( 'label' => $slot['label'] . ' — ' . __( 'Button Text', 'vindecodertheme' ), 'section' => 'vindecoder_affiliate', 'type' => 'text' )
		);

		$wp_customize->add_setting( $url_id, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control(
			$url_id,
			array( 'label' => $slot['label'] . ' — ' . __( 'Link URL', 'vindecodertheme' ), 'description' => $slot['description'], 'section' => 'vindecoder_affiliate', 'type' => 'url' )
		);
	}

	$wp_customize->add_setting(
		'vindecoder_affiliate_disclosure',
		array(
			'default'           => __( 'This section may contain affiliate links. If you buy something after clicking one, we may earn a small commission at no extra cost to you.', 'vindecodertheme' ),
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);
	$wp_customize->add_control(
		'vindecoder_affiliate_disclosure',
		array( 'label' => __( 'Disclosure Text', 'vindecodertheme' ), 'section' => 'vindecoder_affiliate', 'type' => 'textarea' )
	);
}
add_action( 'customize_register', 'vindecoder_customize_register_affiliate' );

function vindecoder_get_active_affiliate_links() {
	$links = array();
	foreach ( vindecoder_get_affiliate_slots() as $key => $slot ) {
		$url = get_theme_mod( 'vindecoder_affiliate_' . $key . '_url', '' );
		if ( empty( $url ) ) {
			continue;
		}
		$links[] = array(
			'key'   => $key,
			'label' => get_theme_mod( 'vindecoder_affiliate_' . $key . '_label', $slot['label'] ),
			'url'   => $url,
		);
	}
	return $links;
}

/**
 * ==========================================================================
 * 10. DEFAULT PAGES ON ACTIVATION
 * ==========================================================================
 * Provisions the 5 brand pages (assigned the decoder template automatically)
 * plus Privacy Policy, Terms, Disclaimer, and Contact. Existing pages are
 * never overwritten.
 */
function vindecoder_get_default_pages() {
	$today = esc_html( date_i18n( get_option( 'date_format' ) ) );

	$pages = array(
		'privacy-policy' => array(
			'title'   => __( 'Privacy Policy', 'vindecodertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>
<h2>Overview</h2>
<p>This Privacy Policy explains what information this website (\"the Site\") collects. When you enter a VIN into one of our decoder tools, that VIN is sent to our server, which forwards it to the free public NHTSA vPIC vehicle database to retrieve factory specifications, and returns the result to your browser. We do not store submitted VINs against any personal identifier, and we do not know who you are when you use the tool.</p>
<h2>What We Cache</h2>
<p>To avoid repeatedly querying the same VIN, decoded results are cached on our server for a short period, keyed only to the VIN itself — not to your IP address, browser, or any account (we don't have accounts).</p>
<h2>Advertising</h2>
<p>This Site may display ads served by third-party vendors, including Google, which may use cookies to serve ads based on your prior visits to this or other websites. You may opt out of personalized advertising via Google's Ads Settings or <a href=\"https://www.aboutads.info/choices/\" rel=\"nofollow noopener\" target=\"_blank\">www.aboutads.info</a>.</p>
<h2>Contact</h2>
<p>Questions about this policy can be sent via our <a href=\"" . esc_url( home_url( '/contact/' ) ) . "\">Contact page</a>.</p>",
		),
		'terms-of-service' => array(
			'title'   => __( 'Terms of Service', 'vindecodertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>
<h2>1. Description of Service</h2>
<p>This Site provides free VIN decoding tools that query the publicly available NHTSA vPIC database. We are not affiliated with BMW, Audi, Porsche, Lexus, Land Rover/Range Rover, or any other vehicle manufacturer named on this Site; all trademarks belong to their respective owners and are used only to describe the vehicles our tool decodes.</p>
<h2>2. No Warranty on Accuracy</h2>
<p>Decode results are sourced from NHTSA's public vPIC database and are provided \"as is,\" without warranty of completeness or accuracy. Always verify critical details (recalls, title status, accident history) through an official manufacturer, dealer, or paid vehicle history report before making a purchase decision.</p>
<h2>3. Not a Vehicle History or Title Report</h2>
<p>This tool decodes factory build data encoded in the VIN. It does not check title status, accident history, odometer records, or liens. For that, use a dedicated vehicle history report service.</p>
<h2>4. Third-Party Links and Advertising</h2>
<p>This Site may display advertisements and affiliate links to third-party services (such as vehicle history report providers). We are not responsible for the content, accuracy, or business practices of any linked third party.</p>
<h2>5. Limitation of Liability</h2>
<p>To the fullest extent permitted by law, the Site operator shall not be liable for any damages arising from decisions made using information from this Site's tools.</p>
<h2>6. Contact</h2>
<p>Questions can be sent via our <a href=\"" . esc_url( home_url( '/contact/' ) ) . "\">Contact page</a>.</p>",
		),
		'disclaimer' => array(
			'title'   => __( 'Disclaimer', 'vindecodertheme' ),
			'content' => "<p><em>Last updated: {$today}</em></p>
<h2>Not an Official Manufacturer or DMV Source</h2>
<p>This Site is an independent tool and is not operated by, affiliated with, or endorsed by BMW, Audi, Porsche, Lexus, Land Rover, the NHTSA, or any DMV. Decode results come from NHTSA's public vPIC database, a free federal data source — not from manufacturer build-sheet systems, so factory-installed options and packages are not included.</p>
<h2>Accuracy Limitations</h2>
<p>VIN decoding is pattern-based. In rare cases (recalled VIN formats, pre-production vehicles, or data-entry variance at manufacture), a decode may be incomplete or inconsistent with the physical vehicle. Always cross-check critical details against the vehicle's title, registration, or a manufacturer-authorized dealer before a purchase.</p>
<h2>Not a Substitute for a Vehicle History or Pre-Purchase Inspection</h2>
<p>A VIN decode tells you what the car was built as — not its condition, accident history, or title status. Always pair it with a vehicle history report and an independent pre-purchase inspection before buying a used vehicle.</p>",
		),
		'contact' => array(
			'title'   => __( 'Contact', 'vindecodertheme' ),
			'content' => '<p>' . esc_html__( 'Found a bug, have a question, or want to suggest a brand we should add? We\'d like to hear from you.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'Email us at:', 'vindecodertheme' ) . ' <a href="mailto:support@example.com">support@example.com</a></p>
<p><em>' . esc_html__( 'Please replace this placeholder address with your own support email before launching your site.', 'vindecodertheme' ) . '</em></p>',
		),
	);

	// One page per brand, using the shared decoder template.
	foreach ( vindecoder_get_brands() as $slug => $brand ) {
		$pages[ $slug ] = array(
			'title'    => $brand['title'],
			'content'  => '', // Content is rendered entirely by template-vin-decoder.php.
			'template' => 'template-vin-decoder.php',
		);
	}

	return $pages;
}

function vindecoder_provision_default_pages() {
	$page_ids = array();

	foreach ( vindecoder_get_default_pages() as $slug => $page ) {
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
			if ( ! empty( $page['template'] ) ) {
				update_post_meta( $post_id, '_wp_page_template', $page['template'] );
			}
		}
	}

	if ( ! empty( $page_ids['privacy-policy'] ) && ! get_option( 'wp_page_for_privacy_policy' ) ) {
		update_option( 'wp_page_for_privacy_policy', $page_ids['privacy-policy'] );
	}

	vindecoder_provision_menus( $page_ids );
}
add_action( 'after_switch_theme', 'vindecoder_provision_default_pages' );

function vindecoder_provision_menus( $page_ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	if ( ! has_nav_menu( 'primary' ) ) {
		$menu_id = wp_create_nav_menu( __( 'Primary Menu', 'vindecodertheme' ) );
		if ( ! is_wp_error( $menu_id ) ) {
			wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => __( 'Home', 'vindecodertheme' ), 'menu-item-url' => home_url( '/' ), 'menu-item-status' => 'publish' ) );

			foreach ( vindecoder_get_brands() as $slug => $brand ) {
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

			if ( ! empty( $page_ids['contact'] ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array( 'menu-item-object-id' => $page_ids['contact'], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' )
				);
			}

			$locations['primary'] = $menu_id;
		}
	}

	if ( ! has_nav_menu( 'footer' ) ) {
		$menu_id = wp_create_nav_menu( __( 'Footer Menu', 'vindecodertheme' ) );
		if ( ! is_wp_error( $menu_id ) ) {
			foreach ( array( 'privacy-policy', 'terms-of-service', 'disclaimer' ) as $slug ) {
				if ( empty( $page_ids[ $slug ] ) ) {
					continue;
				}
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array( 'menu-item-object-id' => $page_ids[ $slug ], 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' )
				);
			}
			$locations['footer'] = $menu_id;
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}
