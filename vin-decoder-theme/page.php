<?php
/**
 * Standard page template — used for Privacy Policy, Terms, Disclaimer,
 * Contact, and any other static page that isn't a brand decoder page.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>

	<header class="vd-page-header">
		<div class="vd-container vd-container--narrow">
			<h1><?php the_title(); ?></h1>
		</div>
	</header>

	<div class="vd-page-content">
		<div class="vd-container vd-container--narrow vd-prose">
			<?php the_content(); ?>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="vd-page-links">' . esc_html__( 'Pages:', 'vindecodertheme' ),
					'after'  => '</div>',
				)
			);
			?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php
endwhile;
?>

<?php
get_footer();
