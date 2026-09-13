<?php
/**
 * Template Name: Blog Index
 *
 * A controlled listing of the latest posts, independent of WordPress's
 * Reading-settings "posts page" mechanism — assign this template to a
 * page (default slug: "blog") and it queries + lists posts itself.
 *
 * @package VinDecoderTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$vd_paged = max( 1, get_query_var( 'paged' ), get_query_var( 'page' ) );
$vd_blog_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'paged'          => $vd_paged,
	)
);
?>

<header class="vd-page-header">
	<div class="vd-container vd-container--narrow">
		<h1><?php the_title(); ?></h1>
	</div>
</header>

<div class="vd-page-content">
	<div class="vd-container">
		<?php if ( $vd_blog_query->have_posts() ) : ?>
			<div class="vd-post-list">
				<?php
				while ( $vd_blog_query->have_posts() ) :
					$vd_blog_query->the_post();
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
						'total'     => $vd_blog_query->max_num_pages,
						'current'   => $vd_paged,
						'prev_text' => esc_html__( '&laquo; Previous', 'vindecodertheme' ),
						'next_text' => esc_html__( 'Next &raquo;', 'vindecodertheme' ),
					)
				);
				?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No articles yet — check back soon.', 'vindecodertheme' ); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
