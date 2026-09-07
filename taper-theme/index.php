<?php
/**
 * The main template file — fallback archive/blog listing.
 *
 * @package TaperTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<div class="tt-section">
	<div class="tt-container">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="tt-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<h1><?php single_post_title(); ?></h1>
				</header>
			<?php elseif ( is_archive() ) : ?>
				<header class="tt-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<?php the_archive_title( '<h1>', '</h1>' ); ?>
					<?php the_archive_description( '<div class="tt-text-muted">', '</div>' ); ?>
				</header>
			<?php elseif ( is_search() ) : ?>
				<header class="tt-page-header" style="margin: 0 0 2rem; border: none; background: none; padding: 0;">
					<h1>
						<?php
						printf(
							/* translators: %s: search query. */
							esc_html__( 'Search Results for: %s', 'tapertheme' ),
							'<span>' . esc_html( get_search_query() ) . '</span>'
						);
						?>
					</h1>
				</header>
			<?php endif; ?>

			<div class="tt-post-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'tt-post-card' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>" style="display:block; margin-bottom:1rem; border-radius:12px; overflow:hidden;">
								<?php the_post_thumbnail( 'tapertheme-card' ); ?>
							</a>
						<?php endif; ?>

						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

						<div class="tt-post-meta">
							<?php
							printf(
								/* translators: 1: published date, 2: author name. */
								esc_html__( 'Published %1$s by %2$s', 'tapertheme' ),
								esc_html( get_the_date() ),
								esc_html( get_the_author() )
							);
							?>
						</div>

						<div class="tt-entry-summary">
							<?php the_excerpt(); ?>
						</div>

						<p><a href="<?php the_permalink(); ?>" class="tt-btn tt-btn-secondary"><?php esc_html_e( 'Read More', 'tapertheme' ); ?></a></p>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<div class="tt-pagination">
				<?php
				echo paginate_links(
					array(
						'prev_text' => esc_html__( '&laquo; Previous', 'tapertheme' ),
						'next_text' => esc_html__( 'Next &raquo;', 'tapertheme' ),
					)
				);
				?>
			</div>

		<?php else : ?>

			<div class="tt-card tt-text-center" style="max-width: 640px; margin: 0 auto;">
				<h1><?php esc_html_e( 'Nothing Found', 'tapertheme' ); ?></h1>
				<p><?php esc_html_e( 'It looks like nothing matches your search. Try a different keyword.', 'tapertheme' ); ?></p>
				<?php get_search_form(); ?>
			</div>

		<?php endif; ?>

	</div>
</div>

<?php
get_footer();
