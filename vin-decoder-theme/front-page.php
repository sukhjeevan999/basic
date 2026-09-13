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
				<?php foreach ( vindecoder_get_brands() as $slug => $brand ) : ?>
					<?php $vd_page = get_page_by_path( $slug ); ?>
					<?php if ( $vd_page instanceof WP_Post ) : ?>
						<a class="vd-brand-card" href="<?php echo esc_url( get_permalink( $vd_page ) ); ?>">
							<div class="vd-brand-name"><?php echo esc_html( $brand['label'] ); ?></div>
							<div class="vd-brand-cta"><?php esc_html_e( 'Decode a', 'vindecodertheme' ); ?> <?php echo esc_html( $brand['label'] ); ?> <?php esc_html_e( 'VIN →', 'vindecodertheme' ); ?></div>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>

<section class="vd-content-section">
	<div class="vd-container">
		<div class="vd-prose">

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
					<div class="vd-faq-answer"><p><?php esc_html_e( 'This site covers 25 major car and motorcycle brands (see the list above), all decoded through the same free NHTSA database. Pick the closest brand — the decoder will tell you if a VIN doesn\'t match that manufacturer.', 'vindecodertheme' ); ?></p></div>
				</details>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
