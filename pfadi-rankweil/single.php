<?php
/**
 * Einzelner Beitrag.
 *
 * @package pfadi-rankweil
 */

get_header();

while ( have_posts() ) :
	the_post();
	$pfadi_classic = 'hero' !== pfadi_mod( 'pfadi_single_header', 'classic' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' . ( $pfadi_classic ? ' single-post--classic' : '' ) ); ?>>
		<?php if ( ! $pfadi_classic ) : ?>
			<header class="post-hero<?php echo has_post_thumbnail() ? ' has-image' : ''; ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="post-hero__media"><?php the_post_thumbnail( 'pfadi-hero', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></div>
				<?php endif; ?>
				<div class="container container--narrow post-hero__inner">
					<?php pfadi_breadcrumbs(); ?>
					<?php pfadi_category_pill(); ?>
					<h1 class="post-hero__title"><?php the_title(); ?></h1>
					<?php pfadi_post_meta(); ?>
				</div>
			</header>
		<?php endif; ?>

		<div class="container layout">
			<div class="layout__main">
				<?php if ( $pfadi_classic ) : ?>
					<?php // Klassisch wie bei HitMag: Titel und Beitragsbild in der Textspalte. ?>
					<header class="post-head">
						<?php pfadi_breadcrumbs(); ?>
						<?php pfadi_category_pill(); ?>
						<h1 class="post-head__title"><?php the_title(); ?></h1>
						<?php pfadi_post_meta(); ?>
					</header>
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="post-head__image">
							<?php the_post_thumbnail( 'large', array( 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
							<?php if ( get_the_post_thumbnail_caption() ) : ?>
								<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
							<?php endif; ?>
						</figure>
					<?php endif; ?>
				<?php endif; ?>
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Seiten:', 'pfadi-rankweil' ),
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<footer class="entry-footer">
					<?php the_tags( '<div class="tags">', '', '</div>' ); ?>
					<?php pfadi_share_links(); ?>
				</footer>

				<?php
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-label">' . esc_html__( '← Vorheriger Bericht', 'pfadi-rankweil' ) . '</span><span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-label">' . esc_html__( 'Nächster Bericht →', 'pfadi-rankweil' ) . '</span><span class="nav-title">%title</span>',
					)
				);

				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</article>

	<?php
	if ( pfadi_mod( 'pfadi_related', true ) ) :
		$related = new WP_Query(
			array(
				'posts_per_page'      => 3,
				'post__not_in'        => array( get_the_ID() ),
				'category__in'        => wp_get_post_categories( get_the_ID() ),
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);
		if ( $related->have_posts() ) :
			?>
			<section class="section related">
				<div class="container">
					<header class="section-head">
						<h2 class="section-title"><?php esc_html_e( 'Das könnte dich auch interessieren', 'pfadi-rankweil' ); ?></h2>
					</header>
					<div class="card-grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							get_template_part( 'template-parts/card' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
	endif;
endwhile;

get_footer();
