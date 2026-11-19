<?php
/**
 * Startseite: letzte Berichte.
 *
 * @package pfadi-rankweil
 */

$args = array(
	'posts_per_page'      => max( 1, (int) pfadi_mod( 'pfadi_news_count', 6 ) ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);
$excl = pfadi_excluded_cat_ids();
if ( $excl ) {
	$args['category__not_in'] = $excl;
}
$cat  = (int) pfadi_mod( 'pfadi_news_cat', 0 );
if ( $cat ) {
	$args['cat'] = $cat;
}
if ( pfadi_mod( 'pfadi_news_skip_hero', true ) && ! empty( $GLOBALS['pfadi_shown'] ) ) {
	$args['post__not_in'] = $GLOBALS['pfadi_shown'];
}
$q = new WP_Query( $args );
if ( ! $q->have_posts() ) {
	return;
}

$posts_page = get_option( 'page_for_posts' );
$more_url   = $cat ? get_category_link( $cat ) : ( $posts_page ? get_permalink( $posts_page ) : home_url( '/page/2/' ) );
$title      = pfadi_mod( 'pfadi_news_title', 'Letzte Berichte' );
$more_text  = pfadi_mod( 'pfadi_news_more_text', 'Alle Berichte' );
?>
<section class="section news">
	<div class="container">
		<header class="section-head section-head--row">
			<div>
				<span class="eyebrow"><?php esc_html_e( 'Aus der Gruppe', 'pfadi-rankweil' ); ?></span>
				<h2 class="section-title"><?php echo esc_html( $title ); ?></h2>
			</div>
			<?php if ( $more_text ) : ?>
				<a class="btn btn--outline" href="<?php echo esc_url( $more_url ); ?>"><?php echo esc_html( $more_text ); ?> <?php echo pfadi_icon( 'arrow' ); // phpcs:ignore ?></a>
			<?php endif; ?>
		</header>
		<div class="card-grid">
			<?php
			while ( $q->have_posts() ) :
				$q->the_post();
				get_template_part( 'template-parts/card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
