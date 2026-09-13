<?php
/**
 * The homepage template — brand selector hub. Each brand card links to its
 * own dedicated, exact-match-keyword decoder page (template-vin-decoder.php).
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="vd-hero" id="vd-top">
	<div class="vd-container">
		<div class="vd-hero-head">
			<span class="vd-eyebrow"><?php esc_html_e( 'Free VIN Decoder', 'vindecodertheme' ); ?></span>
			<h1><?php esc_html_e( 'Decode Any VIN in Seconds', 'vindecodertheme' ); ?></h1>
			<p><?php esc_html_e( 'Pick your brand below to reveal your vehicle\'s model, year, engine, and factory build details — free, instant, and powered by the official U.S. NHTSA vehicle database.', 'vindecodertheme' ); ?></p>
		</div>

		<div class="vd-card" style="max-width: 960px; margin: 0 auto;">
			<h2 class="vd-text-center" style="margin-bottom: var(--vd-space-6);"><?php esc_html_e( 'Choose Your Brand', 'vindecodertheme' ); ?></h2>
			<div class="vd-brand-grid">
				<?php foreach ( vindecoder_get_featured_brand_slugs() as $slug ) : ?>
					<?php $brand = vindecoder_get_brand( $slug ); ?>
					<?php $vd_page = $brand ? get_page_by_path( $slug ) : null; ?>
					<?php if ( $vd_page instanceof WP_Post ) : ?>
						<a class="vd-brand-card" href="<?php echo esc_url( get_permalink( $vd_page ) ); ?>">
							<div class="vd-brand-name"><?php echo esc_html( $brand['label'] ); ?></div>
							<div class="vd-brand-cta"><?php esc_html_e( 'Decode a', 'vindecodertheme' ); ?> <?php echo esc_html( $brand['label'] ); ?> <?php esc_html_e( 'VIN →', 'vindecodertheme' ); ?></div>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>

		<div class="vd-trust-row">
			<div class="vd-trust-item">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2 4 5v6c0 5 3.4 8.7 8 10 4.6-1.3 8-5 8-10V5l-8-3Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
				<span><?php esc_html_e( 'Official U.S. NHTSA data', 'vindecodertheme' ); ?></span>
			</div>
			<div class="vd-trust-item">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M5 12l4-4M5 12l4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<span><?php esc_html_e( '25 brands, one decoder each', 'vindecodertheme' ); ?></span>
			</div>
			<div class="vd-trust-item">
				<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<span><?php esc_html_e( '100% free, no sign-up', 'vindecodertheme' ); ?></span>
			</div>
		</div>
	</div>
</section>

<section class="vd-content-section">
	<div class="vd-container">
		<div class="vd-prose">

			<h2><?php esc_html_e( 'What Is a VIN Number?', 'vindecodertheme' ); ?></h2>
			<p><?php esc_html_e( 'A Vehicle Identification Number is a fixed 17-character code every manufacturer stamps onto a vehicle at the factory. It\'s not random — three sections of it each encode something specific:', 'vindecodertheme' ); ?></p>

			<div class="vd-vin-diagram">
				<div class="vd-vin-diagram-group is-wmi">
					<div class="vd-vin-diagram-chars">
						<?php foreach ( str_split( 'WMI' ) as $vd_char ) : ?><span class="vd-vin-char"><?php echo esc_html( $vd_char ); ?></span><?php endforeach; ?>
					</div>
					<div class="vd-vin-diagram-label"><?php esc_html_e( 'Positions 1–3: manufacturer & country of origin', 'vindecodertheme' ); ?></div>
				</div>
				<div class="vd-vin-diagram-group is-vds">
					<div class="vd-vin-diagram-chars">
						<?php foreach ( str_split( 'VDSVDS' ) as $vd_char ) : ?><span class="vd-vin-char"><?php echo esc_html( $vd_char ); ?></span><?php endforeach; ?>
					</div>
					<div class="vd-vin-diagram-label"><?php esc_html_e( 'Positions 4–9: model, body style, engine, check digit', 'vindecodertheme' ); ?></div>
				</div>
				<div class="vd-vin-diagram-group is-vis">
					<div class="vd-vin-diagram-chars">
						<?php foreach ( str_split( 'VISVISVIS' ) as $vd_char ) : ?><span class="vd-vin-char"><?php echo esc_html( $vd_char ); ?></span><?php endforeach; ?>
					</div>
					<div class="vd-vin-diagram-label"><?php esc_html_e( 'Positions 10–17: model year, assembly plant, serial number', 'vindecodertheme' ); ?></div>
				</div>
			</div>

			<p><?php esc_html_e( 'Every decoder on this site reads exactly this structure against the free public NHTSA vPIC database and hands you back a plain-English summary — no manual lookup tables, no guesswork.', 'vindecodertheme' ); ?></p>

			<h2><?php esc_html_e( 'What a VIN Decode Actually Tells You', 'vindecodertheme' ); ?></h2>
			<p><?php esc_html_e( 'Every vehicle\'s 17-character Vehicle Identification Number encodes real factory data: the manufacturer, model, model year, body style, engine, and the plant where it was built. Our decoders read that structure using the free public NHTSA vPIC database — the same reference data used by insurers, DMVs, and dealers across the United States, and by used-car shoppers as far as the United Kingdom, Canada, and Australia — and turn it into a plain-English summary in seconds.', 'vindecodertheme' ); ?></p>
			<p><?php esc_html_e( 'This is not a vehicle history report. It won\'t tell you about accidents, title status, or prior owners — for that you\'ll want a dedicated history report service. What it will tell you, independently of what any seller or listing claims, is exactly what the vehicle was built as.', 'vindecodertheme' ); ?></p>

			<h2><?php esc_html_e( 'Why Decode Before You Buy', 'vindecodertheme' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Confirm a used-car listing actually matches the model, trim, and engine described.', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'Check a partial VIN from a photo or ad before you commit to seeing the car in person.', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'Verify factory specifications for insurance, import, or registration paperwork.', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'Settle a forum or marketplace disagreement about what a specific VIN actually decodes to.', 'vindecodertheme' ); ?></li>
			</ul>

			<h2 id="faq"><?php esc_html_e( 'Frequently Asked Questions', 'vindecodertheme' ); ?></h2>
			<div class="vd-faq">
				<details class="vd-faq-item" open>
					<summary><?php esc_html_e( 'Is this free, and do I need to sign up?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'Yes to both — completely free, no account or email required. Every brand decoder on this site works the same way.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Where does the decode data come from?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'The NHTSA vPIC database — a free, public vehicle-specification database maintained by the U.S. National Highway Traffic Safety Administration. We are not affiliated with any vehicle manufacturer.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Does this include factory options and packages?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'No — factory-installed options and packages require manufacturer build-sheet data, which isn\'t part of the public NHTSA database. This tool covers model, year, engine, body style, and plant of manufacture.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Can I decode a motorcycle, RV, or other vehicle type?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'This site covers 25 major car and motorcycle brands — 5 featured above, and the rest under the Car / Bike menu or in the footer — all decoded through the same free NHTSA database. Pick the closest brand — the decoder will tell you if a VIN doesn\'t match that manufacturer.', 'vindecodertheme' ); ?></p></div>
				</details>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
