<?php
/**
 * The footer for our theme.
 *
 * @package TaperTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main><!-- #tt-main -->

<footer class="tt-site-footer">
	<div class="tt-container">
		<div class="tt-footer-grid">
			<div>
				<div class="tt-footer-brand"><?php bloginfo( 'name' ); ?></div>
				<p class="tt-footer-desc">
					<?php
					echo esc_html(
						get_bloginfo( 'description' )
							? get_bloginfo( 'description' )
							: __( 'A free, printable quit smoking schedule template and reduction calendar generator — personalized to your baseline, with progress tracking built in.', 'tapertheme' )
					);
					?>
				</p>

				<?php $tt_social_links = tapertheme_get_active_social_links(); ?>
				<?php if ( $tt_social_links ) : ?>
					<ul class="tt-social-links">
						<?php foreach ( $tt_social_links as $tt_link ) : ?>
							<li>
								<a
									href="<?php echo esc_url( $tt_link['url'] ); ?>"
									class="tt-social-icon"
									target="_blank"
									rel="noopener noreferrer"
									aria-label="<?php echo esc_attr( $tt_link['label'] ); ?>"
								>
									<?php echo $tt_link['icon']; // phpcs:ignore WordPress.Security.EscapeOutput -- fixed, theme-defined SVG markup, not user input. ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<div class="tt-footer-heading"><?php esc_html_e( 'Resources', 'tapertheme' ); ?></div>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'items_wrap'     => '<ul class="tt-footer-links">%3$s</ul>',
							'depth'          => 1,
						)
					);
					?>
				<?php else : ?>
					<ul class="tt-footer-links">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Reduction Schedule Tool', 'tapertheme' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/#faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'tapertheme' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'tapertheme' ); ?></a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'tapertheme' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>

			<div>
				<div class="tt-footer-heading"><?php esc_html_e( 'Legal', 'tapertheme' ); ?></div>
				<ul class="tt-footer-links">
					<?php
					$privacy_id = (int) get_option( 'wp_page_for_privacy_policy' );
					$privacy_url = $privacy_id ? get_permalink( $privacy_id ) : home_url( '/privacy-policy/' );
					?>
					<li><a href="<?php echo esc_url( $privacy_url ); ?>"><?php esc_html_e( 'Privacy Policy', 'tapertheme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'tapertheme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/medical-disclaimer/' ) ); ?>"><?php esc_html_e( 'Medical Disclaimer', 'tapertheme' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="tt-footer-bottom">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'tapertheme' ); ?></p>
			<p class="tt-footer-legal-note">
				<?php esc_html_e( 'This site provides educational, self-help planning tools only and does not offer medical advice, diagnosis, or treatment. See our', 'tapertheme' ); ?>
				<a href="<?php echo esc_url( home_url( '/medical-disclaimer/' ) ); ?>"><?php esc_html_e( 'Medical Disclaimer', 'tapertheme' ); ?></a>.
			</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
