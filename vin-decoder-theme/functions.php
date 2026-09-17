<?php
/**
 * VinDecoderTheme functions and definitions.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once get_template_directory() . '/inc/default-posts.php';

/**
 * Bump this on every CSS/JS change. It's appended as ?ver=... on every
 * enqueued stylesheet/script, which is what forces browsers AND any
 * server-side cache (Hostinger/LiteSpeed, a caching plugin, etc.) to
 * fetch the new file instead of serving a stale cached copy under the
 * same URL. Forgetting to bump this is why a real, correct code change
 * can still show up broken/unstyled on the live site.
 */
define( 'VINDECODER_VERSION', '1.12.0' );

/**
 * Bump this whenever vindecoder_get_default_pages()/get_default_posts()
 * adds, removes, or edits an entry, or the menu structure changes — this
 * is a one-shot gate (see vindecoder_maybe_reprovision() below), so once
 * it's run at a given number it won't run again at that same number even
 * if a page/post was since deleted on the live site. Forgetting to bump
 * this is exactly why a manually-deleted post can fail to come back.
 */
define( 'VINDECODER_PROVISION_VERSION', '6' );

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
	$templates['template-blog-index.php']  = __( 'Blog Index', 'vindecodertheme' );
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

	// Cookie consent banner — every page, not just the decoder tool pages,
	// since it governs analytics/ads cookies site-wide.
	wp_enqueue_script( 'vindecoder-cookie-consent', get_template_directory_uri() . '/assets/js/cookie-consent.js', array(), VINDECODER_VERSION, true );
	$vindecoder_privacy_page = get_page_by_path( 'privacy-policy' );
	wp_localize_script(
		'vindecoder-cookie-consent',
		'VinDecoderCookieConfig',
		array(
			'privacyUrl' => $vindecoder_privacy_page ? get_permalink( $vindecoder_privacy_page ) : home_url( '/privacy-policy/' ),
		)
	);

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
				'ajaxUrl'      => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
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
 * 5. AJAX — SERVER-SIDE NHTSA PROXY
 * ==========================================================================
 * The browser calls this same-origin admin-ajax.php endpoint instead of the
 * NHTSA vPIC API directly, which avoids any CORS issues and lets us
 * validate/sanitize the VIN server-side before forwarding the request. No
 * API key, no cost — the NHTSA vPIC API is a free public service of the
 * U.S. Dept. of Transportation.
 *
 * This used to be a custom WP REST API route (/wp-json/vindecoder/v1/decode).
 * Switched to admin-ajax.php because WordPress core's REST API runs a global
 * cookie/nonce check (rest_cookie_check_errors) on every REST request when
 * the visiting browser holds a valid wp-admin login cookie — completely
 * independent of this route's own permission_callback, which was already
 * public. Anyone testing the tool while logged into wp-admin on that same
 * browser (e.g. after visiting wp-admin from a phone) would see it fail with
 * WordPress's own "Cookie check failed" error, especially on a cached page
 * whose baked-in nonce no longer matches. admin-ajax.php has no such
 * built-in check, so it isn't exposed to this failure mode at all.
 *
 * Deliberately no nonce check here: this action is read-only (it never
 * changes state — decoded results are cached, keyed only by VIN), so there
 * is no CSRF to defend against, and a nonce would only reintroduce the same
 * cached-page-vs-logged-in-session mismatch problem described above.
 */
function vindecoder_ajax_decode() {
	$vin    = isset( $_GET['vin'] ) ? strtoupper( trim( sanitize_text_field( wp_unslash( $_GET['vin'] ) ) ) ) : '';
	$result = vindecoder_decode_vin_core( $vin );

	if ( is_wp_error( $result ) ) {
		$error_data = $result->get_error_data();
		$status     = ( is_array( $error_data ) && ! empty( $error_data['status'] ) ) ? (int) $error_data['status'] : 400;
		status_header( $status );
		wp_send_json( array( 'message' => $result->get_error_message() ) );
	}

	wp_send_json( $result );
}
add_action( 'wp_ajax_vindecoder_decode', 'vindecoder_ajax_decode' );
add_action( 'wp_ajax_nopriv_vindecoder_decode', 'vindecoder_ajax_decode' );

function vindecoder_decode_vin_core( $vin ) {
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
		return $cached;
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

	return $result;
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
	$blog_page = get_page_by_path( 'blog' );
	if ( $blog_page instanceof WP_Post ) {
		echo '<li><a href="' . esc_url( get_permalink( $blog_page ) ) . '">' . esc_html__( 'Blog', 'vindecodertheme' ) . '</a></li>';
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
 * Structured data for blog posts (Article + FAQPage). Runs on every
 * single post, not just the ones seeded from inc/default-posts.php —
 * Article schema uses standard WP post data either way. FAQPage schema
 * only appears when the post has an 'faqs' array in that source file,
 * matched by slug, so it never gets emitted without an actual matching
 * FAQ section already visible in the post content.
 */
function vindecoder_post_schema() {
	if ( ! is_single() || 'post' !== get_post_type() ) {
		return;
	}

	$post_obj      = get_post();
	$author_name   = get_theme_mod( 'vindecoder_author_name', '' );
	$article_schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title(),
		'description'      => get_the_excerpt(),
		'datePublished'    => get_the_date( DATE_W3C ),
		'dateModified'     => get_the_modified_date( DATE_W3C ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => $author_name ? $author_name : get_the_author(),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		),
		'mainEntityOfPage' => get_permalink(),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $article_schema ) . '</script>' . "\n";

	// FAQPage schema, only if this post's entry in inc/default-posts.php
	// includes an 'faqs' array — matched by slug, must mirror the visible
	// on-page FAQ word-for-word.
	$default_posts = vindecoder_get_default_posts();
	$post_data     = $default_posts[ $post_obj->post_name ] ?? null;
	if ( empty( $post_data['faqs'] ) ) {
		return;
	}

	$faq_entities = array();
	foreach ( $post_data['faqs'] as $item ) {
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
add_action( 'wp_head', 'vindecoder_post_schema' );

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
			// The [vindecoder_author_box] shortcode below pulls name/photo/
			// role from Appearance > Customize > About / Author — set those
			// there rather than editing this HTML. The rest of the story is
			// written to be true for whoever actually built and runs the site.
			'content' => '<p><em>' . esc_html__( 'A personal note from the person who built this site.', 'vindecodertheme' ) . '</em></p>

<h2>' . esc_html__( 'Why I Built This', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'A while back I was looking at a used BMW and wanted to check the VIN before I put down a deposit on it. I typed "BMW VIN check" into Google and got a mess of results — half of them wanted a credit card before showing me anything, and the free ones either timed out, buried the result under five popups, or gave me a decode that didn\'t line up with what the seller was telling me.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'All I wanted was a straight answer: is this a real VIN, and what does it actually say about the car — model, year, engine, where it was built. The boring factual stuff that doesn\'t lie to you.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'So I built it myself. This site pulls straight from NHTSA\'s own public vehicle database — the same records insurers and DMVs use — and shows you exactly what comes back, with nothing hidden behind a paywall. No account, no credit card, no "one free search then pay up." I started with the BMW decoder because that\'s the car I was actually checking that day, then kept adding brands as people asked for their own.', 'vindecodertheme' ) . '</p>
<p>' . esc_html__( 'It\'s still 100% free and unlimited to use, and I don\'t plan on changing that. If it saved me a headache, I figured it would save someone else one too.', 'vindecodertheme' ) . '</p>

[vindecoder_author_box]

<h2>' . esc_html__( 'What This Site Is (and Isn\'t)', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'It\'s a free VIN decoder. Enter a VIN, get back the factory specs — nothing more, nothing less. It won\'t tell you if a car\'s been in an accident or had its odometer rolled back; for that you need an actual vehicle history report. What it will tell you, independent of anything a seller says, is exactly what the manufacturer built.', 'vindecodertheme' ) . '</p>

<h2>' . esc_html__( 'Questions or Feedback?', 'vindecodertheme' ) . '</h2>
<p>' . esc_html__( 'If something looks wrong, a brand\'s missing, or the tool just saved you from a bad purchase — I\'d like to hear about it.', 'vindecodertheme' ) . ' <a href="' . esc_url( home_url( '/contact/' ) ) . '">' . esc_html__( 'Get in touch here', 'vindecodertheme' ) . '</a>.</p>',
		),
		'blog' => array(
			'title'    => __( 'Blog', 'vindecodertheme' ),
			'content'  => '', // Content is rendered entirely by template-blog-index.php.
			'template' => 'template-blog-index.php',
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
	vindecoder_provision_default_posts();
}
add_action( 'after_switch_theme', 'vindecoder_provision_default_pages' );

/**
 * Maps a brand slug to the post slug(s) (from inc/default-posts.php)
 * written specifically about it — shown as a "Related Reading" card
 * right below the tool on that brand's page (template-vin-decoder.php).
 * A brand with no matching article yet just shows nothing there.
 */
function vindecoder_get_related_post_slugs() {
	return array(
		'bmw'             => array( 'bmw-vin-decoder-chassis-code' ),
		'audi'            => array( 'audi-vin-position-breakdown' ),
		'ford'            => array( 'ford-vin-decoder-build-sheet' ),
		'jeep'            => array( 'jeep-vin-decoder-guide' ),
		'toyota'          => array( 'toyota-vin-code-decoder' ),
		'porsche'         => array( 'porsche-vin-decoder-guide' ),
		'harley-davidson' => array( 'harley-davidson-vin-decoder-guide' ),
		'tesla'           => array( 'tesla-vin-decoder-models' ),
		'chevrolet'       => array( 'chevrolet-vin-decoder-guide' ),
		'range-rover'     => array( 'range-rover-vs-land-rover-vin' ),
		'kia'             => array( 'kia-vs-hyundai-vin-decoder' ),
		'hyundai'         => array( 'kia-vs-hyundai-vin-decoder' ),
	);
}

/**
 * Same never-overwrite-if-it-exists pattern as the default pages above,
 * for the theme's starter blog posts (see inc/default-posts.php). Runs
 * as part of the same provisioning pass, so it's also covered by
 * vindecoder_maybe_reprovision() below -- no separate reactivation step.
 */
function vindecoder_provision_default_posts() {
	foreach ( vindecoder_get_default_posts() as $slug => $post ) {
		$existing = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $existing instanceof WP_Post ) {
			continue;
		}

		wp_insert_post(
			array(
				'post_title'   => $post['title'],
				'post_name'    => $slug,
				'post_content' => $post['content'],
				'post_excerpt' => $post['excerpt'],
				'post_status'  => 'publish',
				'post_type'    => 'post',
			),
			true
		);
	}
}

/**
 * Relying on after_switch_theme alone means new default pages/menu
 * structure (e.g. this update's About page) only ever get created if
 * someone actually switches the theme away and back after uploading —
 * an easy step to forget, and each time it's missed the new page
 * 404s ("Nothing Found") even though the code creating it is live.
 * This re-runs provisioning once per bumped VINDECODER_PROVISION_VERSION,
 * automatically, the next time wp-admin loads at all — no manual
 * reactivation needed. Bump the version constant whenever
 * vindecoder_get_default_pages() gains/changes a page.
 */
function vindecoder_maybe_reprovision() {
	if ( get_option( 'vindecoder_provision_version' ) === VINDECODER_PROVISION_VERSION ) {
		return;
	}
	vindecoder_provision_default_pages();
	update_option( 'vindecoder_provision_version', VINDECODER_PROVISION_VERSION );
}
add_action( 'admin_init', 'vindecoder_maybe_reprovision' );

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

		foreach ( array( 'about', 'blog' ) as $vd_static_slug ) {
			if ( empty( $page_ids[ $vd_static_slug ] ) ) {
				continue;
			}
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-object-id' => $page_ids[ $vd_static_slug ],
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

/**
 * ==========================================================================
 * 11. FULL THEME CUSTOMIZATION (Appearance > Customize)
 * ==========================================================================
 * Color palette, typography, layout width, header/footer options, and the
 * About/Author name+photo — all live-editable without touching code. Site
 * logo, title, and tagline are already covered by WordPress's own built-in
 * Site Identity panel (custom-logo theme support, added in section 2).
 *
 * Defaults below match style.css's hardcoded :root values exactly, so an
 * untouched install looks pixel-identical to before this section existed —
 * nothing changes until someone actually edits a setting.
 */
function vindecoder_customizer_defaults() {
	return array(
		'color_primary'       => '#1d4ed8',
		'color_accent'        => '#d97a1f',
		'color_bg_light'      => '#f5f7fa',
		'color_text_light'    => '#131b2e',
		'color_bg_dark'       => '#10141f',
		'color_text_dark'     => '#eef1f8',
		'heading_font'        => 'Manrope',
		'body_font'           => 'system',
		'container_width'     => 'standard',
		'header_show_tagline' => false,
		'header_sticky'       => true,
		'footer_tagline'      => '17 characters in. The full story out.',
		'author_name'         => '',
		'author_photo'        => '',
		'author_bio'          => __( 'Founder', 'vindecodertheme' ),
		'social_x'            => '',
		'social_facebook'     => '',
		'social_instagram'    => '',
		'social_linkedin'     => '',
		'social_pinterest'    => '',
	);
}

/**
 * ==========================================================================
 * SOCIAL LINKS
 * ==========================================================================
 * URLs come from the Customizer (Social Links section) — nothing hardcoded,
 * and a platform with no URL set just doesn't render an icon anywhere.
 */
function vindecoder_get_social_platforms() {
	return array(
		'x'         => __( 'X (Twitter)', 'vindecodertheme' ),
		'facebook'  => __( 'Facebook', 'vindecodertheme' ),
		'instagram' => __( 'Instagram', 'vindecodertheme' ),
		'linkedin'  => __( 'LinkedIn', 'vindecodertheme' ),
		'pinterest' => __( 'Pinterest', 'vindecodertheme' ),
	);
}

function vindecoder_get_social_links() {
	$links = array();
	foreach ( array_keys( vindecoder_get_social_platforms() ) as $platform ) {
		$url = get_theme_mod( 'vindecoder_social_' . $platform, '' );
		if ( $url ) {
			$links[ $platform ] = $url;
		}
	}
	return $links;
}

/**
 * Simple single-color line icons, not pixel-exact brand marks — same
 * approach as the generic car/bike icons used elsewhere, kept minimal on
 * purpose. currentColor so they pick up whatever color the surrounding
 * CSS sets (header vs. footer vs. hover).
 */
function vindecoder_get_social_icon_svg( $platform ) {
	$icons = array(
		'x'         => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4l16 16M20 4 4 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		'facebook'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 8.5h2.5V5H14c-2.2 0-4 1.8-4 4v2H8v3.5h2V21h3.5v-6.5H16l.5-3.5h-3V9c0-.6.4-1 1-1Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>',
		'instagram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="4.5" stroke="currentColor" stroke-width="1.6"/><circle cx="12" cy="12" r="3.6" stroke="currentColor" stroke-width="1.6"/><circle cx="16.6" cy="7.4" r="1" fill="currentColor"/></svg>',
		'linkedin'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3" stroke="currentColor" stroke-width="1.6"/><circle cx="8.2" cy="8.5" r="1.1" fill="currentColor"/><path d="M8.2 11v6M12 11v6M12 13.6c0-1.4 1-2.6 2.4-2.6s2.4 1 2.4 2.6V17" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
		'pinterest' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6"/><path d="M9.5 18c.6-2.4 1.4-5.6 1.9-7.7a2.6 2.6 0 0 1 5-.4c.5 1.6-.4 4-2.2 4-1.1 0-1.8-.8-1.6-1.9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>',
	);
	return $icons[ $platform ] ?? '';
}

/**
 * Shared markup for header and footer — nothing renders at all if no
 * social URLs are set yet in the Customizer.
 */
function vindecoder_social_icons_html( $extra_class = '' ) {
	$links = vindecoder_get_social_links();
	if ( empty( $links ) ) {
		return '';
	}
	$platforms = vindecoder_get_social_platforms();
	$html      = '<div class="vd-social-icons' . ( $extra_class ? ' ' . esc_attr( $extra_class ) : '' ) . '">';
	foreach ( $links as $platform => $url ) {
		$html .= '<a href="' . esc_url( $url ) . '" class="vd-social-icon" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $platforms[ $platform ] ) . '">' . vindecoder_get_social_icon_svg( $platform ) . '</a>';
	}
	$html .= '</div>';
	return $html;
}

function vindecoder_sanitize_checkbox( $checked ) {
	return ( true === $checked || '1' === $checked || 1 === $checked );
}

function vindecoder_sanitize_select( $input, $setting ) {
	$input   = sanitize_text_field( $input );
	$control = $setting->manager->get_control( $setting->id );
	$choices = $control ? $control->choices : array();
	return array_key_exists( $input, $choices ) ? $input : $setting->default;
}

// ---- Small color-math helpers so a handful of picked colors cascade into
// a full, coherent palette (hover shades, subtle tints, borders) instead of
// requiring a dozen confusing individual color fields. ----
function vindecoder_hex_to_rgb( $hex ) {
	$hex = ltrim( (string) $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return array( 29, 78, 216 ); // Fallback: default primary blue.
	}
	return array( hexdec( substr( $hex, 0, 2 ) ), hexdec( substr( $hex, 2, 2 ) ), hexdec( substr( $hex, 4, 2 ) ) );
}

function vindecoder_mix_color( $hex, $target_hex, $weight ) {
	$a     = vindecoder_hex_to_rgb( $hex );
	$b     = vindecoder_hex_to_rgb( $target_hex );
	$mixed = array();
	for ( $i = 0; $i < 3; $i++ ) {
		$mixed[ $i ] = max( 0, min( 255, round( $a[ $i ] + ( $b[ $i ] - $a[ $i ] ) * $weight ) ) );
	}
	return sprintf( '#%02x%02x%02x', $mixed[0], $mixed[1], $mixed[2] );
}

function vindecoder_customize_register_theme_options( $wp_customize ) {
	$d = vindecoder_customizer_defaults();

	// ---- Color Palette ----
	$wp_customize->add_section(
		'vindecoder_colors',
		array(
			'title'       => __( 'Color Palette', 'vindecodertheme' ),
			'description' => __( 'Pick 6 colors and every button, card, badge, and border shade is derived from them automatically — for both light and dark mode.', 'vindecodertheme' ),
			'priority'    => 40,
		)
	);
	$color_fields = array(
		'color_primary'    => __( 'Primary Color (buttons, links, accents)', 'vindecodertheme' ),
		'color_accent'     => __( 'Accent Color (highlights, badges)', 'vindecodertheme' ),
		'color_bg_light'   => __( 'Light Mode — Background', 'vindecodertheme' ),
		'color_text_light' => __( 'Light Mode — Text', 'vindecodertheme' ),
		'color_bg_dark'    => __( 'Dark Mode — Background', 'vindecodertheme' ),
		'color_text_dark'  => __( 'Dark Mode — Text', 'vindecodertheme' ),
	);
	foreach ( $color_fields as $key => $label ) {
		$id = 'vindecoder_' . $key;
		$wp_customize->add_setting( $id, array( 'default' => $d[ $key ], 'sanitize_callback' => 'sanitize_hex_color' ) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $label, 'section' => 'vindecoder_colors' ) )
		);
	}

	// ---- Typography ----
	$wp_customize->add_section( 'vindecoder_typography', array( 'title' => __( 'Typography', 'vindecodertheme' ), 'priority' => 41 ) );

	$heading_fonts = array(
		'Manrope'          => __( 'Manrope (default)', 'vindecodertheme' ),
		'Poppins'          => 'Poppins',
		'Montserrat'       => 'Montserrat',
		'Playfair Display' => 'Playfair Display',
		'Merriweather'     => 'Merriweather',
	);
	$wp_customize->add_setting( 'vindecoder_heading_font', array( 'default' => $d['heading_font'], 'sanitize_callback' => 'vindecoder_sanitize_select' ) );
	$wp_customize->add_control( 'vindecoder_heading_font', array( 'label' => __( 'Heading Font', 'vindecodertheme' ), 'section' => 'vindecoder_typography', 'type' => 'select', 'choices' => $heading_fonts ) );

	$body_fonts = array(
		'system'    => __( 'System Default (fastest to load)', 'vindecodertheme' ),
		'Inter'     => 'Inter',
		'Roboto'    => 'Roboto',
		'Open Sans' => 'Open Sans',
		'Lato'      => 'Lato',
	);
	$wp_customize->add_setting( 'vindecoder_body_font', array( 'default' => $d['body_font'], 'sanitize_callback' => 'vindecoder_sanitize_select' ) );
	$wp_customize->add_control( 'vindecoder_body_font', array( 'label' => __( 'Body Font', 'vindecodertheme' ), 'section' => 'vindecoder_typography', 'type' => 'select', 'choices' => $body_fonts ) );

	// ---- Layout ----
	$wp_customize->add_section( 'vindecoder_layout', array( 'title' => __( 'Layout', 'vindecodertheme' ), 'priority' => 42 ) );
	$wp_customize->add_setting( 'vindecoder_container_width', array( 'default' => $d['container_width'], 'sanitize_callback' => 'vindecoder_sanitize_select' ) );
	$wp_customize->add_control(
		'vindecoder_container_width',
		array(
			'label'   => __( 'Page Content Width', 'vindecodertheme' ),
			'section' => 'vindecoder_layout',
			'type'    => 'select',
			'choices' => array(
				'narrow'   => __( 'Narrow (960px)', 'vindecodertheme' ),
				'standard' => __( 'Standard (1120px, default)', 'vindecodertheme' ),
				'wide'     => __( 'Wide (1320px)', 'vindecodertheme' ),
			),
		)
	);

	// ---- Header ----
	$wp_customize->add_section( 'vindecoder_header', array( 'title' => __( 'Header', 'vindecodertheme' ), 'priority' => 43 ) );
	$wp_customize->add_setting( 'vindecoder_header_show_tagline', array( 'default' => $d['header_show_tagline'], 'sanitize_callback' => 'vindecoder_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vindecoder_header_show_tagline', array( 'label' => __( 'Show the site tagline next to the logo', 'vindecodertheme' ), 'section' => 'vindecoder_header', 'type' => 'checkbox' ) );
	$wp_customize->add_setting( 'vindecoder_header_sticky', array( 'default' => $d['header_sticky'], 'sanitize_callback' => 'vindecoder_sanitize_checkbox' ) );
	$wp_customize->add_control( 'vindecoder_header_sticky', array( 'label' => __( 'Keep the header fixed at the top while scrolling', 'vindecodertheme' ), 'section' => 'vindecoder_header', 'type' => 'checkbox' ) );

	// ---- Footer ----
	$wp_customize->add_section( 'vindecoder_footer', array( 'title' => __( 'Footer', 'vindecodertheme' ), 'priority' => 44 ) );
	$wp_customize->add_setting( 'vindecoder_footer_tagline', array( 'default' => $d['footer_tagline'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vindecoder_footer_tagline', array( 'label' => __( 'Footer Tagline', 'vindecodertheme' ), 'section' => 'vindecoder_footer', 'type' => 'text' ) );

	// ---- About / Author ----
	$wp_customize->add_section(
		'vindecoder_author',
		array(
			'title'       => __( 'About / Author', 'vindecodertheme' ),
			'description' => __( 'Powers the author box on the homepage and the About page — no code editing needed.', 'vindecodertheme' ),
			'priority'    => 45,
		)
	);
	$wp_customize->add_setting( 'vindecoder_author_name', array( 'default' => $d['author_name'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vindecoder_author_name', array( 'label' => __( 'Your Name', 'vindecodertheme' ), 'section' => 'vindecoder_author', 'type' => 'text' ) );

	$wp_customize->add_setting( 'vindecoder_author_photo', array( 'default' => $d['author_photo'], 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control(
		new WP_Customize_Image_Control( $wp_customize, 'vindecoder_author_photo', array( 'label' => __( 'Your Photo', 'vindecodertheme' ), 'section' => 'vindecoder_author' ) )
	);

	$wp_customize->add_setting( 'vindecoder_author_bio', array( 'default' => $d['author_bio'], 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'vindecoder_author_bio', array( 'label' => __( 'Role (e.g. "Founder")', 'vindecodertheme' ), 'section' => 'vindecoder_author', 'type' => 'text' ) );

	// ---- Social Links ----
	$wp_customize->add_section(
		'vindecoder_social',
		array(
			'title'       => __( 'Social Links', 'vindecodertheme' ),
			'description' => __( 'Shows as small icons in the header and footer. Leave a field blank to hide that icon everywhere.', 'vindecodertheme' ),
			'priority'    => 46,
		)
	);
	foreach ( vindecoder_get_social_platforms() as $vindecoder_social_key => $vindecoder_social_label ) {
		$vindecoder_social_id = 'vindecoder_social_' . $vindecoder_social_key;
		$wp_customize->add_setting( $vindecoder_social_id, array( 'default' => $d[ 'social_' . $vindecoder_social_key ], 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control(
			$vindecoder_social_id,
			array(
				/* translators: %s: social platform name, e.g. Instagram. */
				'label'   => sprintf( __( '%s URL', 'vindecodertheme' ), $vindecoder_social_label ),
				'section' => 'vindecoder_social',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'vindecoder_customize_register_theme_options' );

/**
 * Renders all customizer choices as one inline <style> block, computing
 * hover/tint/border shades from the 6 chosen colors. A few small decorative
 * accents elsewhere in style.css (e.g. the FAQ mismatch-notice tint) are
 * hardcoded literals rather than variables and won't shift with the
 * palette — everything else (buttons, links, header, hero, cards, nav,
 * footer, badges) reads these same custom properties already.
 */
function vindecoder_customizer_css() {
	$d = vindecoder_customizer_defaults();

	$primary     = get_theme_mod( 'vindecoder_color_primary', $d['color_primary'] );
	$accent      = get_theme_mod( 'vindecoder_color_accent', $d['color_accent'] );
	$bg_light    = get_theme_mod( 'vindecoder_color_bg_light', $d['color_bg_light'] );
	$text_light  = get_theme_mod( 'vindecoder_color_text_light', $d['color_text_light'] );
	$bg_dark     = get_theme_mod( 'vindecoder_color_bg_dark', $d['color_bg_dark'] );
	$text_dark   = get_theme_mod( 'vindecoder_color_text_dark', $d['color_text_dark'] );
	$heading_font = get_theme_mod( 'vindecoder_heading_font', $d['heading_font'] );
	$body_font    = get_theme_mod( 'vindecoder_body_font', $d['body_font'] );
	$container    = get_theme_mod( 'vindecoder_container_width', $d['container_width'] );
	$header_sticky = get_theme_mod( 'vindecoder_header_sticky', $d['header_sticky'] );

	$container_px = array( 'narrow' => 960, 'standard' => 1120, 'wide' => 1320 );
	$container_px = $container_px[ $container ] ?? 1120;

	$primary_dark        = vindecoder_mix_color( $primary, '#000000', 0.18 );
	$primary_light_light = vindecoder_mix_color( $primary, '#ffffff', 0.88 );
	$primary_light_dark  = vindecoder_mix_color( $primary, $bg_dark, 0.7 );

	$surface_light     = vindecoder_mix_color( $bg_light, '#ffffff', 0.7 );
	$surface_alt_light = vindecoder_mix_color( $bg_light, '#ffffff', 0.35 );
	$border_light      = vindecoder_mix_color( $text_light, $bg_light, 0.82 );

	$surface_dark     = vindecoder_mix_color( $bg_dark, '#ffffff', 0.08 );
	$surface_alt_dark = vindecoder_mix_color( $bg_dark, '#ffffff', 0.14 );
	$border_dark      = vindecoder_mix_color( $text_dark, $bg_dark, 0.75 );

	$body_font_stack = 'system' === $body_font
		? '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif'
		: '"' . $body_font . '", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif';
	?>
	<style id="vindecoder-customizer-css">
		:root {
			--vd-color-primary: <?php echo esc_html( $primary ); ?>;
			--vd-color-primary-dark: <?php echo esc_html( $primary_dark ); ?>;
			--vd-color-primary-light: <?php echo esc_html( $primary_light_light ); ?>;
			--vd-color-accent: <?php echo esc_html( $accent ); ?>;
			--vd-color-bg: <?php echo esc_html( $bg_light ); ?>;
			--vd-color-surface: <?php echo esc_html( $surface_light ); ?>;
			--vd-color-surface-alt: <?php echo esc_html( $surface_alt_light ); ?>;
			--vd-color-border: <?php echo esc_html( $border_light ); ?>;
			--vd-color-text: <?php echo esc_html( $text_light ); ?>;
			--vd-font-heading: "<?php echo esc_html( $heading_font ); ?>", var(--vd-font-base);
			--vd-font-base: <?php echo esc_html( $body_font_stack ); ?>;
			--vd-container: <?php echo (int) $container_px; ?>px;
		}
		:root[data-vd-theme="dark"] {
			--vd-color-primary-light: <?php echo esc_html( $primary_light_dark ); ?>;
			--vd-color-bg: <?php echo esc_html( $bg_dark ); ?>;
			--vd-color-surface: <?php echo esc_html( $surface_dark ); ?>;
			--vd-color-surface-alt: <?php echo esc_html( $surface_alt_dark ); ?>;
			--vd-color-border: <?php echo esc_html( $border_dark ); ?>;
			--vd-color-text: <?php echo esc_html( $text_dark ); ?>;
		}
		<?php if ( ! $header_sticky ) : ?>
		.vd-site-header { position: static; }
		<?php endif; ?>
	</style>
	<?php
	if ( 'Manrope' !== $heading_font ) {
		echo '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css2?family=' . rawurlencode( $heading_font ) . ':wght@700;800&display=swap' ) . '">' . "\n";
	}
	if ( 'system' !== $body_font ) {
		echo '<link rel="stylesheet" href="' . esc_url( 'https://fonts.googleapis.com/css2?family=' . rawurlencode( $body_font ) . ':wght@400;600&display=swap' ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'vindecoder_customizer_css', 20 );

/**
 * [vindecoder_author_box] — the photo + name + role box shown on the
 * homepage and the About page, fully driven by Appearance > Customize >
 * About / Author. Falls back to a neutral placeholder until a name is set.
 */
function vindecoder_author_box_shortcode() {
	$name  = get_theme_mod( 'vindecoder_author_name', '' );
	$photo = get_theme_mod( 'vindecoder_author_photo', '' );
	$role  = get_theme_mod( 'vindecoder_author_bio', __( 'Founder', 'vindecodertheme' ) );

	$display_name = $name ? $name : __( 'Add your name under Appearance → Customize → About / Author', 'vindecodertheme' );

	ob_start();
	?>
	<div class="vd-author-box">
		<div class="vd-author-avatar" aria-hidden="true">
			<?php if ( $photo ) : ?>
				<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ? $name : '' ); ?>">
			<?php else : ?>
				🙂
			<?php endif; ?>
		</div>
		<div class="vd-author-bio">
			<p class="vd-author-name"><?php echo esc_html( $display_name ); ?></p>
			<p class="vd-author-role"><?php echo esc_html( $role ); ?>, <?php bloginfo( 'name' ); ?></p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'vindecoder_author_box', 'vindecoder_author_box_shortcode' );
