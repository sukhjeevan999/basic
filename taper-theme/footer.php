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
							: __( 'A free planning tool to help you build a gradual, structured reduction schedule — and track your progress along the way.', 'tapertheme' )
					);
					?>
				</p>
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
