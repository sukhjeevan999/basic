<?php
/**
 * Standard page template — used for Privacy Policy, Terms of Service,
 * Medical Disclaimer, Contact, and any other static page.
 *
 * @package TaperTheme
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

	<header class="tt-page-header">
		<div class="tt-container tt-container--narrow">
			<h1><?php the_title(); ?></h1>
		</div>
	</header>

	<div class="tt-page-content">
		<div class="tt-container tt-container--narrow tt-prose">

			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin-bottom: 2rem; border-radius: 12px; overflow: hidden;">
					<?php the_post_thumbnail( 'tapertheme-hero' ); ?>
				</div>
			<?php endif; ?>

			<?php the_content(); ?>

			<?php
			wp_link_pages(
				array(
					'before' => '<div class="tt-page-links">' . esc_html__( 'Pages:', 'tapertheme' ),
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
