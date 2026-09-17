<?php
/**
 * Single blog post template.
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
			<div class="vd-post-meta">
				<?php
				printf(
					/* translators: 1: published date, 2: author name. */
					esc_html__( 'Published %1$s by %2$s', 'vindecodertheme' ),
					esc_html( get_the_date() ),
					esc_html( get_the_author() )
				);
				?>
			</div>
		</div>
	</header>

	<div class="vd-page-content">
		<div class="vd-container vd-container--narrow vd-prose">
			<?php if ( has_post_thumbnail() ) : ?>
				<div style="border-radius: var(--vd-radius-md); overflow: hidden; margin-bottom: var(--vd-space-6);">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>

			<?php the_content(); ?>

			<?php
			wp_link_pages(
				array(
					'before' => '<div class="vd-page-links">' . esc_html__( 'Pages:', 'vindecodertheme' ),
					'after'  => '</div>',
				)
			);
			?>

			<?php echo vindecoder_post_share_html(); ?>

			<p><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">&larr; <?php esc_html_e( 'Back to all articles', 'vindecodertheme' ); ?></a></p>

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
