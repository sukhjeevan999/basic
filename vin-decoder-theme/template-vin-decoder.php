<?php
/**
 * Template Name: VIN Decoder — Brand Page
 *
 * Shared template for every brand's dedicated decoder page. Which brand is
 * rendered is determined by the page's slug matching a key in
 * vindecoder_get_brands() (see functions.php) — no per-page custom fields
 * needed. Assign this template to a page whose slug matches any key in
 * vindecoder_get_brands() (e.g. "bmw", "toyota", "harley-davidson") to
 * bring that brand's page online.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$vd_brand = vindecoder_get_current_brand();

if ( ! $vd_brand ) {
	// Page is using this template but its slug doesn't match a known brand
	// — fail safely rather than rendering a broken tool.
	get_header();
	echo '<div class="vd-section vd-container vd-text-center"><p>' . esc_html__( 'This page is not configured for a supported brand yet.', 'vindecodertheme' ) . '</p></div>';
	get_footer();
	return;
}

get_header();
?>

<section class="vd-hero" id="vd-tool">
	<div class="vd-container">

		<div class="vd-hero-head">
			<span class="vd-eyebrow"><?php esc_html_e( 'Free VIN Decoder Tool', 'vindecodertheme' ); ?></span>
			<h1><?php echo esc_html( $vd_brand['title'] ); ?></h1>
			<p><?php echo esc_html( $vd_brand['tagline'] ); ?></p>
		</div>

		<div class="vd-card vd-tool-card" id="vd-tool-card">
			<form id="vd-decode-form" novalidate>
				<div class="vd-vin-field">
					<label for="vd-vin-input"><?php esc_html_e( 'Enter your 17-character VIN', 'vindecodertheme' ); ?></label>
					<input
						type="text"
						id="vd-vin-input"
						name="vin"
						maxlength="17"
						autocomplete="off"
						autocapitalize="characters"
						spellcheck="false"
						placeholder="<?php echo esc_attr( $vd_brand['sample_vin'] ); ?>"
						required
					>
					<div class="vd-char-count" id="vd-char-count">0 / 17</div>
					<span class="vd-hint"><?php esc_html_e( 'Usually found on the driver-side windshield corner, the driver door jamb sticker, or your registration/insurance card.', 'vindecodertheme' ); ?></span>
				</div>

				<div class="vd-form-actions">
					<button type="submit" class="vd-btn vd-btn-primary" id="vd-decode-btn">
						<?php esc_html_e( 'Decode VIN', 'vindecodertheme' ); ?>
					</button>
				</div>

				<div class="vd-form-error" id="vd-form-error" hidden role="alert"></div>
			</form>
		</div>

		<div id="vd-results" hidden aria-live="polite">

			<div class="vd-vin-strip" id="vd-vin-strip"></div>

			<div class="vd-result-grid">
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Make', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-make">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Model', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-model">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Model Year', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-year">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Body Style', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-body">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Engine', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-engine">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Fuel Type', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-fuel">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Drive Type', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-drive">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Transmission', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-transmission">—</div></div>
				<div class="vd-fact"><div class="vd-fact-label"><?php esc_html_e( 'Plant Location', 'vindecodertheme' ); ?></div><div class="vd-fact-value" id="vd-fact-plant">—</div></div>
			</div>

			<div id="vd-brand-mismatch" class="vd-brand-mismatch" hidden></div>

			<div class="vd-results-actions">
				<button type="button" class="vd-btn vd-btn-primary" id="vd-download-pdf"><?php esc_html_e( 'Download as PDF', 'vindecodertheme' ); ?></button>
				<button type="button" class="vd-btn vd-btn-secondary" id="vd-download-image"><?php esc_html_e( 'Download as Image', 'vindecodertheme' ); ?></button>
				<button type="button" class="vd-btn vd-btn-secondary" id="vd-decode-another"><?php esc_html_e( 'Decode Another VIN', 'vindecodertheme' ); ?></button>
			</div>
		</div>

	</div>
</section>

<div class="vd-toast" id="vd-toast" role="status"></div>

<?php $vd_affiliate_links = vindecoder_get_active_affiliate_links(); ?>
<?php if ( $vd_affiliate_links ) : ?>
<section class="vd-content-section" style="padding-top: 0;">
	<div class="vd-container">
		<div class="vd-prose">
			<div class="vd-card" style="background: var(--vd-color-surface-alt);">
				<h2><?php esc_html_e( 'Before You Buy', 'vindecodertheme' ); ?></h2>
				<p class="vd-text-muted" style="font-size: 0.85rem; font-style: italic;"><?php echo esc_html( get_theme_mod( 'vindecoder_affiliate_disclosure', '' ) ); ?></p>
				<div style="display:flex; flex-wrap:wrap; gap: var(--vd-space-3);">
					<?php foreach ( $vd_affiliate_links as $vd_link ) : ?>
						<a href="<?php echo esc_url( $vd_link['url'] ); ?>" class="vd-btn vd-btn-secondary" target="_blank" rel="nofollow sponsored noopener">
							<?php echo esc_html( $vd_link['label'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="vd-content-section">
	<div class="vd-container">
		<div class="vd-prose">

			<h2>
				<?php
				/* translators: %s: brand name, e.g. BMW. */
				echo esc_html( sprintf( __( 'About This %s VIN Decoder', 'vindecodertheme' ), $vd_brand['label'] ) );
				?>
			</h2>
			<p><?php echo esc_html( $vd_brand['intro'] ); ?></p>

			<h2>
				<?php
				/* translators: %s: brand name. */
				echo esc_html( sprintf( __( 'Where to Find Your %s VIN', 'vindecodertheme' ), $vd_brand['label'] ) );
				?>
			</h2>
			<ul>
				<li><?php esc_html_e( 'Driver-side dashboard, visible through the windshield from outside the car.', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'A sticker on the driver-side door jamb (visible when the door is open).', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'Your vehicle registration, title, or insurance card.', 'vindecodertheme' ); ?></li>
				<li><?php esc_html_e( 'The vehicle\'s engine bay, often stamped on the firewall or engine block.', 'vindecodertheme' ); ?></li>
			</ul>

			<h2><?php esc_html_e( 'What This Tool Tells You — and What It Doesn\'t', 'vindecodertheme' ); ?></h2>
			<p><?php esc_html_e( 'This decoder reads the factory data structurally encoded into every VIN — manufacturer, model, model year, body style, engine, and assembly plant — using the free public NHTSA vPIC database. It does not include factory-installed options or packages (that requires manufacturer build-sheet data, which isn\'t public), and it is not a vehicle history report: it won\'t show accidents, title brands, odometer records, or prior owners.', 'vindecodertheme' ); ?></p>

			<div class="vd-disclaimer">
				<h3><span class="vd-disclaimer-badge" aria-hidden="true">&#9888;</span> <?php esc_html_e( 'Disclaimer', 'vindecodertheme' ); ?></h3>
				<p>
					<?php
					echo esc_html(
						sprintf(
							/* translators: %s: brand name. */
							__( 'This tool is independent and not affiliated with, operated by, or endorsed by %s or any other vehicle manufacturer named on this site. Decode results come from the public NHTSA vPIC database and may occasionally be incomplete for unusual or pre-production VINs. Always verify critical details with an authorized dealer, and use a dedicated vehicle history report before making a purchase decision.', 'vindecodertheme' ),
							$vd_brand['label']
						)
					);
					?>
				</p>
			</div>

			<h2 id="faq"><?php esc_html_e( 'Frequently Asked Questions', 'vindecodertheme' ); ?></h2>
			<div class="vd-faq">
				<details class="vd-faq-item" open>
					<summary>
						<?php
						echo esc_html( sprintf( __( 'Is this %s VIN decoder really free?', 'vindecodertheme' ), $vd_brand['label'] ) );
						?>
					</summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'Yes — no sign-up, no payment, no daily limit. It runs on the free public NHTSA vPIC database.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Why does it say my VIN doesn\'t match this brand?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'We still show you the decode — the NHTSA database identifies the actual manufacturer encoded in the VIN, and we flag it if it doesn\'t match the brand of this page, in case you copied the VIN from the wrong vehicle or listing.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Can I decode more than one VIN?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'Yes, as many as you like — click "Decode Another VIN" after each result.', 'vindecodertheme' ); ?></p></div>
				</details>
				<details class="vd-faq-item">
					<summary><?php esc_html_e( 'Does this check for recalls, accidents, or title issues?', 'vindecodertheme' ); ?></summary>
					<div class="vd-faq-answer"><p><?php esc_html_e( 'No — this decodes factory build data only. For recalls, check NHTSA\'s separate recall lookup; for accident and title history, use a dedicated vehicle history report service.', 'vindecodertheme' ); ?></p></div>
				</details>
			</div>

		</div>
	</div>
</section>

<?php
get_footer();
