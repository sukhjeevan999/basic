<?php
/**
 * The footer for our theme.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- #vd-main -->

<footer class="vd-site-footer">
	<div class="vd-container">
		<div class="vd-footer-grid">
			<div>
				<div class="vd-footer-brand"><?php bloginfo( 'name' ); ?></div>
				<p class="vd-footer-desc">
					<?php
					echo esc_html(
						get_bloginfo( 'description' )
							? get_bloginfo( 'description' )
							: __( 'Free VIN decoder tools for 25 major car and motorcycle brands — powered by the official NHTSA vehicle database.', 'vindecodertheme' )
					);
					?>
				</p>
				<p class="vd-footer-tagline"><?php esc_html_e( '17 characters in. The full story out.', 'vindecodertheme' ); ?></p>
			</div>

			<details class="vd-footer-accordion" id="footer-cars">
				<summary class="vd-footer-heading"><?php esc_html_e( 'Cars', 'vindecodertheme' ); ?></summary>
				<ul class="vd-footer-links">
					<?php foreach ( vindecoder_get_brand_slugs_by_category( 'car' ) as $slug ) : ?>
						<?php $vd_brand_page = get_page_by_path( $slug ); ?>
						<?php if ( $vd_brand_page instanceof WP_Post ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $vd_brand_page ) ); ?>"><?php echo esc_html( vindecoder_get_brand( $slug )['label'] ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</details>

			<details class="vd-footer-accordion" id="footer-bikes">
				<summary class="vd-footer-heading"><?php esc_html_e( 'Bikes', 'vindecodertheme' ); ?></summary>
				<ul class="vd-footer-links">
					<?php foreach ( vindecoder_get_brand_slugs_by_category( 'bike' ) as $slug ) : ?>
						<?php $vd_brand_page = get_page_by_path( $slug ); ?>
						<?php if ( $vd_brand_page instanceof WP_Post ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $vd_brand_page ) ); ?>"><?php echo esc_html( vindecoder_get_brand( $slug )['label'] ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</details>

			<div>
				<div class="vd-footer-heading"><?php esc_html_e( 'Legal', 'vindecodertheme' ); ?></div>
				<ul class="vd-footer-links vd-footer-links--static">
					<?php $vd_privacy_id = (int) get_option( 'wp_page_for_privacy_policy' ); ?>
					<li><a href="<?php echo esc_url( $vd_privacy_id ? get_permalink( $vd_privacy_id ) : home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'vindecodertheme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'vindecodertheme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'vindecodertheme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'vindecodertheme' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="vd-footer-bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'vindecodertheme' ); ?></p>
			<p class="vd-footer-legal-note">
				<?php esc_html_e( 'Not affiliated with any vehicle manufacturer. Decode data sourced from the free public NHTSA vPIC database. See our', 'vindecodertheme' ); ?>
				<a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'vindecodertheme' ); ?></a>.
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
