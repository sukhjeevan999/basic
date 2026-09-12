<?php
/**
 * The main template file — fallback archive/blog listing.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="vd-section">
	<div class="vd-container">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="vd-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<h1><?php single_post_title(); ?></h1>
				</header>
			<?php elseif ( is_archive() ) : ?>
				<header class="vd-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<?php the_archive_title( '<h1>', '</h1>' ); ?>
				</header>
			<?php elseif ( is_search() ) : ?>
				<header class="vd-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<h1>
						<?php
						printf(
							/* translators: %s: search query. */
							esc_html__( 'Search Results for: %s', 'vindecodertheme' ),
							'<span>' . esc_html( get_search_query() ) . '</span>'
						);
						?>
					</h1>
				</header>
			<?php endif; ?>

			<div class="vd-post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'vd-post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" style="display:block; margin-bottom:1rem; border-radius:12px; overflow:hidden;">
								<?php the_post_thumbnail( 'vindecoder-card' ); ?>
							</a>
						<?php endif; ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
						<div class="vd-entry-summary"><?php the_excerpt(); ?></div>
						<p><a href="<?php the_permalink(); ?>" class="vd-btn vd-btn-secondary"><?php esc_html_e( 'Read More', 'vindecodertheme' ); ?></a></p>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<div class="vd-pagination">
				<?php
				echo paginate_links(
					array(
						'prev_text' => esc_html__( '&laquo; Previous', 'vindecodertheme' ),
						'next_text' => esc_html__( 'Next &raquo;', 'vindecodertheme' ),
					)
				);
				?>
			</div>

		<?php else : ?>

			<div class="vd-card vd-text-center" style="max-width: 640px; margin: 0 auto;">
				<h1><?php esc_html_e( 'Nothing Found', 'vindecodertheme' ); ?></h1>
				<p><?php esc_html_e( 'It looks like nothing matches your search. Try a different keyword.', 'vindecodertheme' ); ?></p>
				<?php get_search_form(); ?>
			</div>

		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
