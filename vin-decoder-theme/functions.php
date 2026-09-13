<?php
/**
 * VinDecoderTheme functions and definitions.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bump this on every CSS/JS change. It's appended as ?ver=... on every
 * enqueued stylesheet/script, which is what forces browsers AND any
 * server-side cache (Hostinger/LiteSpeed, a caching plugin, etc.) to
 * fetch the new file instead of serving a stale cached copy under the
 * same URL. Forgetting to bump this is why a real, correct code change
 * can still show up broken/unstyled on the live site.
 */
define( 'VINDECODER_VERSION', '1.6.0' );

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
			'category'         => "car",
			'title'             => 'Free BMW VIN Decoder',
			'tagline'           => 'Decode any BMW VIN for free — model, chassis era, engine, and factory build details.',
			'meta_description'  => 'Free BMW VIN decoder. Enter your 17-character VIN to instantly reveal your BMW\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WBA5A5C50FD509130',
			'intro'             => 'BMW build sheets are notoriously hard to track down once a car leaves the factory. A VIN decode won\'t recover the full options list a dealer build sheet has, but it instantly confirms the details that matter most when buying, selling, or researching a BMW: the exact model, model year, engine, and body style encoded into the VIN itself.',
		),
		'audi'        => array(
			'label'             => 'Audi',
			'category'         => "car",
			'title'             => 'Free Audi VIN Decoder',
			'tagline'           => 'Decode any Audi VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Audi VIN decoder. Enter your 17-character VIN to instantly reveal your Audi\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WAUZZZ4GZDN018595',
			'intro'             => 'Whether you\'re buying a used Audi, verifying a listing, or just curious what a partial VIN glimpsed in a photo actually decodes to, a quick VIN check confirms the model, model year, engine, and body style the factory encoded into the car — without needing a dealer login or a paid report.',
		),
		'porsche'     => array(
			'label'             => 'Porsche',
			'category'         => "car",
			'title'             => 'Free Porsche VIN Decoder',
			'tagline'           => 'Decode any Porsche VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Porsche VIN decoder. Enter your 17-character VIN to instantly reveal your Porsche\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'WP0AA2A99JS156123',
			'intro'             => 'Porsche VINs pack in more than most buyers realize — model line, model year, and body style are all encoded directly into the sequence. Before you rely on a seller\'s description alone, a VIN decode gives you an independent, factory-encoded read on what the car actually is.',
		),
		'lexus'       => array(
			'label'             => 'Lexus',
			'category'         => "car",
			'title'             => 'Free Lexus VIN Decoder',
			'tagline'           => 'Decode any Lexus VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Lexus VIN decoder. Enter your 17-character VIN to instantly reveal your Lexus\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'JTHBK1EG8E2123456',
			'intro'             => 'A Lexus VIN encodes its model, model year, engine, and body style at the factory — details worth confirming independently before you buy, sell, or insure the car, rather than relying only on a listing description.',
		),
		'range-rover' => array(
			'label'             => 'Range Rover',
			'category'         => "car",
			'nhtsa_make'        => 'Land Rover',
			'title'             => 'Free Range Rover VIN Decoder',
			'tagline'           => 'Decode any Range Rover VIN for free — model, engine, trim, and factory build details.',
			'meta_description'  => 'Free Range Rover VIN decoder. Enter your 17-character VIN to instantly reveal your Range Rover\'s model, year, engine, body style, and plant of manufacture — powered by the official NHTSA database.',
			'sample_vin'        => 'SALGS2SE6JA123456',
			'intro'             => 'Range Rover model lines (Evoque, Velar, Sport, and the full-size Range Rover) share design language but differ enormously in engine options and factory specification. A VIN decode confirms exactly which one you\'re looking at, straight from the encoded build data.',
		),
		'mercedes-benz'    => array(
			'label'            => 'Mercedes-Benz',
			'category'         => "car",
			'title'            => 'Free Mercedes-Benz VIN Decoder',
			'tagline'          => 'Decode any Mercedes-Benz VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Mercedes-Benz VIN decoder for buyers and owners across California and the rest of the US. Enter your 17-character VIN to instantly reveal your Mercedes-Benz\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => 'WDDGF4HB1CA123456',
			'intro'            => 'From a C-Class listing in California to a used GLE on a dealer lot across the country, a VIN decode gives you an independent, factory-encoded read on the model, model year, engine, and body style — before you trust a seller\'s description alone.',
		),
		'toyota'           => array(
			'label'            => 'Toyota',
			'category'         => "car",
			'title'            => 'Free Toyota VIN Decoder',
			'tagline'          => 'Decode any Toyota VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Toyota VIN decoder for used-car buyers in Texas and nationwide. Enter your 17-character VIN to instantly reveal your Toyota\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '4T1BF1FK5CU123456',
			'intro'            => 'Toyota builds the same nameplate — Camry, Corolla, RAV4 — across several plants and trim levels over the years. Whether you\'re shopping a used Toyota in Texas or anywhere else, a VIN decode confirms exactly what left the factory, independent of what any listing claims.',
		),
		'honda'            => array(
			'label'            => 'Honda',
			'category'         => "car",
			'title'            => 'Free Honda VIN Decoder',
			'tagline'          => 'Decode any Honda VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Honda VIN decoder for buyers in Florida and across the US. Enter your 17-character VIN to instantly reveal your Honda\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '1HGCM82633A123456',
			'intro'            => 'Civic, Accord, CR-V — Honda\'s best-sellers span multiple generations that can look nearly identical from a listing photo. A quick VIN decode settles the model year, engine, and body style question in seconds, whether you\'re buying in Florida or shipping a car across state lines.',
		),
		'ford'             => array(
			'label'            => 'Ford',
			'category'         => "car",
			'title'            => 'Free Ford VIN Decoder',
			'tagline'          => 'Decode any Ford VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Ford VIN decoder for buyers in Michigan and across the US. Enter your 17-character VIN to instantly reveal your Ford\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '1FTFW1ET5EFA23456',
			'intro'            => 'From an F-150 built in Michigan to a Mustang or Explorer anywhere else in the country, Ford VINs encode the exact model, model year, engine, and body style at the factory — useful to confirm before you buy, sell, or insure the truck or car in question.',
		),
		'chevrolet'        => array(
			'label'            => 'Chevrolet',
			'category'         => "car",
			'title'            => 'Free Chevrolet VIN Decoder',
			'tagline'          => 'Decode any Chevrolet VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Chevrolet VIN decoder for buyers in Ohio and across the US. Enter your 17-character VIN to instantly reveal your Chevrolet\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '1G1ZE5ST8JF123456',
			'intro'            => 'Silverado, Malibu, Equinox — Chevrolet\'s lineup is built across multiple plants including facilities in Ohio. A VIN decode confirms exactly which model, year, and engine you\'re looking at, independent of what a used-car listing says.',
		),
		'jeep'             => array(
			'label'            => 'Jeep',
			'category'         => "car",
			'title'            => 'Free Jeep VIN Decoder',
			'tagline'          => 'Decode any Jeep VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Jeep VIN decoder for buyers in Pennsylvania and across the US. Enter your 17-character VIN to instantly reveal your Jeep\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '1C4RJFAG8FC123456',
			'intro'            => 'Wrangler, Grand Cherokee, and Gladiator trims and engines vary a lot year to year. Whether you\'re looking at a listing in Pennsylvania or elsewhere, a VIN decode confirms the factory-encoded model, model year, and engine before you commit.',
		),
		'kia'              => array(
			'label'            => 'Kia',
			'category'         => "car",
			'title'            => 'Free Kia VIN Decoder',
			'tagline'          => 'Decode any Kia VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Kia VIN decoder for buyers in Georgia and across the US. Enter your 17-character VIN to instantly reveal your Kia\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '5XYPHDA2XLG123456',
			'intro'            => 'Kia builds several US-market models, including at its Georgia assembly plant. A VIN decode confirms the exact model, model year, and engine encoded into a specific Kia — handy for verifying a used-car listing before you buy.',
		),
		'hyundai'          => array(
			'label'            => 'Hyundai',
			'category'         => "car",
			'title'            => 'Free Hyundai VIN Decoder',
			'tagline'          => 'Decode any Hyundai VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Hyundai VIN decoder for buyers in North Carolina and across the US. Enter your 17-character VIN to instantly reveal your Hyundai\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '5NPE24AF4FH123456',
			'intro'            => 'Elantra, Sonata, Tucson — Hyundai\'s lineup spans several generations and factories. A VIN decode confirms exactly which model, model year, and engine you\'re looking at, whether you\'re shopping in North Carolina or anywhere else.',
		),
		'volkswagen'       => array(
			'label'            => 'Volkswagen',
			'category'         => "car",
			'title'            => 'Free Volkswagen VIN Decoder',
			'tagline'          => 'Decode any Volkswagen VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Volkswagen VIN decoder for buyers in New York and across the US. Enter your 17-character VIN to instantly reveal your Volkswagen\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => '3VWD07AJ5EM123456',
			'intro'            => 'Jetta, Golf, Tiguan — Volkswagen trims and engines differ meaningfully between model years. A VIN decode confirms the factory-encoded details independent of a seller\'s description, whether you\'re buying in New York or elsewhere.',
		),
		'volvo'            => array(
			'label'            => 'Volvo',
			'category'         => "car",
			'title'            => 'Free Volvo VIN Decoder',
			'tagline'          => 'Decode any Volvo VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Volvo VIN decoder for buyers in Washington state and across the US. Enter your 17-character VIN to instantly reveal your Volvo\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => 'YV4A22PK4J1234567',
			'intro'            => 'Volvo\'s XC and S/V-series overlap in body style but differ a lot in engine and trim. A VIN decode confirms exactly what a specific car is, whether you\'re shopping in Washington state or anywhere else.',
		),
		'jaguar'           => array(
			'label'            => 'Jaguar',
			'category'         => "car",
			'title'            => 'Free Jaguar VIN Decoder',
			'tagline'          => 'Decode any Jaguar VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Jaguar VIN decoder for buyers in the United States and United Kingdom. Enter your 17-character VIN to instantly reveal your Jaguar\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => 'SAJWA2GE4JMK12345',
			'intro'            => 'Jaguar\'s F-Pace, XE, and F-Type share showroom space but differ substantially in engine and factory specification. A VIN decode confirms exactly which one you\'re looking at, whether you\'re researching a listing in the United Kingdom or the US.',
		),
		'tesla'            => array(
			'label'            => 'Tesla',
			'category'         => "car",
			'title'            => 'Free Tesla VIN Decoder',
			'tagline'          => 'Decode any Tesla VIN for free — model, trim, and factory build details.',
			'meta_description' => 'Free Tesla VIN decoder for buyers in Arizona and across the US. Enter your 17-character VIN to instantly reveal your Tesla\'s model, year, body style, and plant of manufacture.',
			'sample_vin'       => '5YJ3E1EA1LF123456',
			'intro'            => 'Model 3, Model Y, Model S, and Model X can be hard to tell apart from a photo alone. A VIN decode confirms the exact model and model year encoded at the factory, whether you\'re shopping in Arizona or anywhere else.',
		),
		'mitsubishi'       => array(
			'label'            => 'Mitsubishi',
			'category'         => "car",
			'title'            => 'Free Mitsubishi VIN Decoder',
			'tagline'          => 'Decode any Mitsubishi VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Mitsubishi VIN decoder for buyers in Illinois and across the US. Enter your 17-character VIN to instantly reveal your Mitsubishi\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => 'JA4AP3AU1KZ123456',
			'intro'            => 'Outlander, Eclipse Cross, Mirage — a VIN decode confirms exactly which Mitsubishi model, model year, and engine you\'re looking at, whether you\'re shopping in Illinois or anywhere else in the country.',
		),
		'bentley'          => array(
			'label'            => 'Bentley',
			'category'         => "car",
			'title'            => 'Free Bentley VIN Decoder',
			'tagline'          => 'Decode any Bentley VIN for free — model, engine, trim, and factory build details.',
			'meta_description' => 'Free Bentley VIN decoder for buyers in the United States and Canada. Enter your 17-character VIN to instantly reveal your Bentley\'s model, year, engine, body style, and plant of manufacture.',
			'sample_vin'       => 'SCBBR9ZA1JC123456',
			'intro'            => 'A used Bentley Continental or Bentayga listing is a big-ticket decision — a VIN decode gives you an independent, factory-encoded confirmation of model, model year, and engine, whether you\'re buying in Canada or the US.',
		),
		'harley-davidson'  => array(
			'label'            => 'Harley-Davidson',
			'category'         => "bike",
			'title'            => 'Free Harley-Davidson VIN Decoder',
			'tagline'          => 'Decode any Harley-Davidson VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free Harley-Davidson VIN decoder for riders in Wisconsin and across the US. Enter your 17-character VIN to instantly reveal your Harley-Davidson\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => '1HD1KB4197Y123456',
			'intro'            => 'From a Sportster to a Road King, Harley-Davidson\'s model lines — built at plants including its home state of Wisconsin — vary a lot year to year. A VIN decode confirms the factory-encoded model, model year, and engine before you buy.',
		),
		'triumph'          => array(
			'label'            => 'Triumph',
			'category'         => "bike",
			'title'            => 'Free Triumph VIN Decoder',
			'tagline'          => 'Decode any Triumph motorcycle VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free Triumph motorcycle VIN decoder for riders in Australia and worldwide. Enter your 17-character VIN to instantly reveal your Triumph\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => 'SMTA11EK9GT123456',
			'intro'            => 'Bonneville, Street Triple, Tiger — Triumph\'s model range shares styling cues but differs a lot in engine and specification. A VIN decode confirms exactly what you\'re looking at, whether you\'re shopping in Australia or elsewhere.',
		),
		'yamaha'           => array(
			'label'            => 'Yamaha',
			'category'         => "bike",
			'title'            => 'Free Yamaha VIN Decoder',
			'tagline'          => 'Decode any Yamaha motorcycle VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free Yamaha motorcycle VIN decoder for riders in New Zealand and worldwide. Enter your 17-character VIN to instantly reveal your Yamaha\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => 'JYARN23E5GA123456',
			'intro'            => 'Yamaha\'s motorcycle lineup — from the YZF-R series to cruisers — spans many model years and engine displacements. A VIN decode confirms the factory-encoded model and year, whether you\'re shopping in New Zealand or anywhere else.',
		),
		'kawasaki'         => array(
			'label'            => 'Kawasaki',
			'category'         => "bike",
			'title'            => 'Free Kawasaki VIN Decoder',
			'tagline'          => 'Decode any Kawasaki motorcycle VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free Kawasaki motorcycle VIN decoder for riders in Ireland and worldwide. Enter your 17-character VIN to instantly reveal your Kawasaki\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => 'JKAZX2N15GA123456',
			'intro'            => 'Ninja, Z-series, Versys — Kawasaki\'s range varies significantly by engine displacement and model year. A VIN decode settles which one you\'re looking at, whether you\'re shopping in Ireland or elsewhere.',
		),
		'ktm'              => array(
			'label'            => 'KTM',
			'category'         => "bike",
			'title'            => 'Free KTM VIN Decoder',
			'tagline'          => 'Decode any KTM motorcycle VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free KTM motorcycle VIN decoder for riders in Colorado and worldwide. Enter your 17-character VIN to instantly reveal your KTM\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => 'VBKJA1401GM123456',
			'intro'            => 'KTM\'s Duke, Adventure, and off-road model lines vary widely in displacement and specification. A VIN decode confirms exactly which model and model year you\'re looking at, whether you\'re shopping in Colorado or elsewhere.',
		),
		'suzuki'           => array(
			'label'            => 'Suzuki',
			'category'         => "bike",
			'title'            => 'Free Suzuki VIN Decoder',
			'tagline'          => 'Decode any Suzuki motorcycle VIN for free — model, engine, and factory build details.',
			'meta_description' => 'Free Suzuki motorcycle VIN decoder for riders in Nevada and worldwide. Enter your 17-character VIN to instantly reveal your Suzuki\'s model, year, engine, and plant of manufacture.',
			'sample_vin'       => 'JS1GT7DA1G2123456',
			'intro'            => 'GSX-R, V-Strom, Boulevard — Suzuki\'s motorcycle range spans many engine sizes and generations. A VIN decode confirms the factory-encoded model and model year, whether you\'re shopping in Nevada or elsewhere.',
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
 * The 5 original brands, shown as prominent cards at the top of the
 * homepage. Every brand (these 5 included) still gets its own Car/Bike
 * nav submenu entry and a plain-list homepage link — "featured" only
 * controls what earns a big card up top.
 */
function vindecoder_get_featured_brand_slugs() {
	return array( 'bmw', 'audi', 'porsche', 'lexus', 'range-rover' );
}

/**
 * All brand slugs (in registry order) whose 'category' matches, e.g.
 * 'car' or 'bike'. Used to build the Car/Bike nav submenus and the
 * homepage's "more brands" lists.
 */
function vindecoder_get_brand_slugs_by_category( $category ) {
	$slugs = array();
	foreach ( vindecoder_get_brands() as $slug => $brand ) {
		if ( isset( $brand['category'] ) && $category === $brand['category'] ) {
			$slugs[] = $slug;
		}
	}
	return $slugs;
}

/**
 * A small generic car/motorcycle line icon for a brand's category.
 * Deliberately generic rather than an actual manufacturer logo — brand
 * logos (the BMW roundel, Audi rings, etc.) are registered trademarks,
 * and displaying them on a decoder tool that isn't licensed by those
 * manufacturers risks a trademark/passing-off problem. A plain vehicle
 * icon carries no such risk and still visually distinguishes cars from
 * motorcycles. Echo-ready (static, trusted markup — not user input).
 */
function vindecoder_get_category_icon_svg( $category ) {
	if ( 'bike' === $category ) {
		return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="5.5" cy="17.5" r="3.5" stroke="currentColor" stroke-width="1.6"/><circle cx="18.5" cy="17.5" r="3.5" stroke="currentColor" stroke-width="1.6"/><path d="M5.5 17.5 9 10h5l1.5 3M9 10 7.5 7h-2M14 10l2 4h2.5M11.5 14h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
	return '<svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3.5 16v-2.5L5 9.8A2 2 0 0 1 6.9 8.5h10.2A2 2 0 0 1 19 9.8l1.5 3.7V16" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M3.5 16h17M7 8.5l1-3h8l1 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><circle cx="7" cy="16.5" r="1.8" stroke="currentColor" stroke-width="1.6"/><circle cx="17" cy="16.5" r="1.8" stroke="currentColor" stroke-width="1.6"/></svg>';
}

/**
 * The Car/Bike nav groups, each with the menu label and the footer
 * anchor (id="footer-cars" / id="footer-bikes" in footer.php, present
 * on every page) its parent link jumps to. Shared by
 * vindecoder_provision_menus() and vindecoder_fallback_primary_menu()
 * so both build the exact same structure.
 */
function vindecoder_get_nav_category_groups() {
	return array(
		'car'  => array( 'title' => __( 'Car', 'vindecodertheme' ), 'anchor' => 'footer-cars' ),
		'bike' => array( 'title' => __( 'Bike', 'vindecodertheme' ), 'anchor' => 'footer-bikes' ),
	);
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
			$hints[] = array( 'href' => 'https://vpic.nhtsa.dot.gov' );
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
		$title_parts['title'] = __( 'Free VIN Decoder — BMW, Mercedes, Toyota, Ford & 21 More Brands', 'vindecodertheme' );
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

	// NOTE: the correct vPIC host is vpic.nhtsa.dot.gov — NOT vpic.nhtsa.gov,
	// which has no valid DNS records. Using the wrong host was the root
	// cause of every "Could not resolve host" failure seen during launch.
	$api_url  = 'https://vpic.nhtsa.dot.gov/api/vehicles/DecodeVinValuesExtended/' . rawurlencode( $vin ) . '?format=json';
	$response = wp_remote_get(
		$api_url,
		array(
			'timeout' => 12,
			'headers' => array( 'Accept' => 'application/json' ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error(
			'vindecoder_upstream_error',
			__( 'The vehicle database could not be reached right now. Please try again in a moment.', 'vindecodertheme' ),
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
/**
 * Only ever renders if vindecoder_provision_menus() hasn't run yet (e.g.
 * theme files updated without reactivating). Mirrors the same Home /
 * Car (+ submenu) / Bike (+ submenu) / Contact structure — including the
 * "menu-item-has-children" / "sub-menu" classes wp_nav_menu()'s default
 * walker would output — so the dropdown CSS/JS behaves the same either way.
 */
function vindecoder_fallback_primary_menu() {
	echo '<ul id="primary-menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'vindecodertheme' ) . '</a></li>';

	$about_page = get_page_by_path( 'about' );
	if ( $about_page instanceof WP_Post ) {
		echo '<li><a href="' . esc_url( get_permalink( $about_page ) ) . '">' . esc_html__( 'About', 'vindecodertheme' ) . '</a></li>';
	}

	foreach ( vindecoder_get_nav_category_groups() as $category => $group ) {
		$brand_slugs = vindecoder_get_brand_slugs_by_category( $category );
		if ( empty( $brand_slugs ) ) {
			continue;
		}
		echo '<li class="menu-item-has-children"><a href="#' . esc_attr( $group['anchor'] ) . '">' . esc_html( $group['title'] ) . '</a><ul class="sub-menu">';
		foreach ( $brand_slugs as $slug ) {
			$page = get_page_by_path( $slug );
			if ( $page instanceof WP_Post ) {
				$brand = vindecoder_get_brand( $slug );
				echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $brand['label'] ) . '</a></li>';
			}
		}
		echo '</ul></li>';
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

	// FAQPage schema — must mirror the on-page FAQ in
	// template-vin-decoder.php word-for-word (Google/AI answer engines
	// penalize or ignore schema that doesn't match visible page content).
	$faq_items = array(
		array(
			'question' => sprintf( __( 'Is this %s VIN decoder really free?', 'vindecodertheme' ), $brand['label'] ),
			'answer'   => __( 'Yes — no sign-up, no payment, no daily limit. It runs on the free public NHTSA vPIC database.', 'vindecodertheme' ),
		),
		array(
			'question' => __( 'Why does it say my VIN doesn\'t match this brand?', 'vindecodertheme' ),
			'answer'   => __( 'We still show you the decode — the NHTSA database identifies the actual manufacturer encoded in the VIN, and we flag it if it doesn\'t match the brand of this page, in case you copied the VIN from the wrong vehicle or listing.', 'vindecodertheme' ),
		),
		array(
			'question' => __( 'Can I decode more than one VIN?', 'vindecodertheme' ),
			'answer'   => __( 'Yes, as many as you like — click "Decode Another VIN" after each result.', 'vindecodertheme' ),
		),
		array(
			'question' => __( 'Does this check for recalls, accidents, or title issues?', 'vindecodertheme' ),
			'answer'   => __( 'No — this decodes factory build data only. For recalls, check NHTSA\'s separate recall lookup; for accident and title history, use a dedicated vehicle history report service.', 'vindecodertheme' ),
		),
	);

	$faq_entities = array();
	foreach ( $faq_items as $item ) {
		$faq_entities[] = array(
			'@type'          => 'Question',
			'name'           => $item['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $item['answer'],
			),
		);
	}

	$faq_schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $faq_entities,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema ) . '</script>' . "\n";
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

	$brand_labels     = wp_list_pluck( vindecoder_get_brands(), 'label' );
	$brand_list_human = implode( ', ', array_slice( $brand_labels, 0, -1 ) ) . ', or ' . end( $brand_labels );

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
<p>This Site provides free VIN decoding tools that query the publicly available NHTSA vPIC database. We are not affiliated with {$brand_list_human}, or any other vehicle manufacturer named on this Site; all trademarks belong to their respective owners and are used only to describe the vehicles our tool decodes.</p>
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
<p>This Site is an independent tool and is not operated by, affiliated with, or endorsed by {$brand_list_human}, the NHTSA, or any DMV. Decode results come from NHTSA's public vPIC database, a free federal data source — not from manufacturer build-sheet systems, so factory-installed options and packages are not included.</p>
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
		'about' => array(
			'title'   => __( 'About', 'vindecodertheme' ),
			// Edit the "[Your Name]" bits below (and swap the placeholder
			// avatar for a real photo, right in the WordPress editor) once
			// you're ready — the rest of the story is written to be true
			// for whoever actually built and runs the site.
			'content' => '<p><em>' . esc_html__( 'A personal note from the person who built this site.', 'vindecodertheme' ) . '</em></p>

<h2>' . esc_html__( 'Why I Built This', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'A while back I was looking at a used BMW and wanted to check the VIN before I put down a deposit on it. I typed "BMW VIN check" into Google and got a mess of results — half of them wanted a credit card before showing me anything, and the free ones either timed out, buried the result under five popups, or gave me a decode that didn\'t line up with what the seller was telling me.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'All I wanted was a straight answer: is this a real VIN, and what does it actually say about the car — model, year, engine, where it was built. The boring factual stuff that doesn\'t lie to you.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'So I built it myself. This site pulls straight from NHTSA\'s own public vehicle database — the same records insurers and DMVs use — and shows you exactly what comes back, with nothing hidden behind a paywall. No account, no credit card, no "one free search then pay up." I started with the BMW decoder because that\'s the car I was actually checking that day, then kept adding brands as people asked for their own.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'It\'s still 100% free and unlimited to use, and I don\'t plan on changing that. If it saved me a headache, I figured it would save someone else one too.', 'vindecodertheme' ) . '</p>

<div class="vd-author-box">
	<div class="vd-author-avatar" aria-hidden="true">🙂</div>
	<div class="vd-author-bio">
		<p class="vd-author-name">[Your Name]</p>
		<p class="vd-author-role">' . esc_html__( 'Founder', 'vindecodertheme' ) . ', ' . esc_html( get_bloginfo( 'name' ) ) . '</p>
	</div>
</div>

<h2>' . esc_html__( 'What This Site Is (and Isn\'t)', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'It\'s a free VIN decoder. Enter a VIN, get back the factory specs — nothing more, nothing less. It won\'t tell you if a car\'s been in an accident or had its odometer rolled back; for that you need an actual vehicle history report. What it will tell you, independent of anything a seller says, is exactly what the manufacturer built.', 'vindecodertheme' ) . '</p>

<h2>' . esc_html__( 'Questions or Feedback?', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'If something looks wrong, a brand\'s missing, or the tool just saved you from a bad purchase — I\'d like to hear about it.', 'vindecodertheme' ) . ' <a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Get in touch here', 'vindecodertheme' ) . '</a>.</p>',
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

/**
 * Builds the primary nav as: Home, Car (submenu of every 'car'-category
 * brand), Bike (submenu of every 'bike'-category brand), Contact.
 *
 * Runs on every theme (re)activation and fully rebuilds the primary
 * menu's items every time from the current vindecoder_get_brands()
 * registry, rather than trying to incrementally patch an existing menu.
 * This is deliberate: WP nav menu items are pure structure (no visitor
 * data lives on them), so wiping and rebuilding is simpler and more
 * reliable than diffing — a brand added, renamed, or recategorized in
 * the registry is reflected correctly every time, with no drift.
 * The menu ID/location assignment itself is preserved (created once,
 * reused after), so Appearance > Menus customizations to menu *display*
 * settings aren't affected, only which items exist.
 */
function vindecoder_provision_menus( $page_ids ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_array( $locations ) ) {
		$locations = array();
	}

	$menu_id = ! empty( $locations['primary'] ) ? (int) $locations['primary'] : 0;
	if ( ! $menu_id || ! wp_get_nav_menu_object( $menu_id ) ) {
		$created = wp_create_nav_menu( __( 'Primary Menu', 'vindecodertheme' ) );
		$menu_id = is_wp_error( $created ) ? 0 : $created;
	}

	if ( $menu_id ) {
		foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $existing_item ) {
			wp_delete_post( $existing_item->ID, true );
		}

		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => __( 'Home', 'vindecodertheme' ),
				'menu-item-url'    => home_url( '/' ),
				'menu-item-status' => 'publish',
			)
		);

		if ( ! empty( $page_ids['about'] ) ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-object-id' => $page_ids['about'],
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}

		foreach ( vindecoder_get_nav_category_groups() as $category => $group ) {
			$brand_slugs = vindecoder_get_brand_slugs_by_category( $category );
			if ( empty( $brand_slugs ) ) {
				continue;
			}

			$parent_id = wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $group['title'],
					'menu-item-url'    => '#' . $group['anchor'],
					'menu-item-status' => 'publish',
				)
			);
			if ( is_wp_error( $parent_id ) ) {
				continue;
			}

			foreach ( $brand_slugs as $slug ) {
				if ( empty( $page_ids[ $slug ] ) ) {
					continue;
				}
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-parent-id' => $parent_id,
						'menu-item-object-id' => $page_ids[ $slug ],
						'menu-item-object'    => 'page',
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
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
