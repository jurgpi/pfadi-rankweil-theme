<?php
/**
 * Aufmacher: neueste Beiträge als Bento-Raster.
 *
 * @package pfadi-rankweil
 */

$args = array(
	'posts_per_page'      => 5,
	// Wie bei HitMag: neueste Beiträge nach Datum. Fixierte („oben gehaltene“) Beiträge
	// nur vorziehen, wenn im Customizer ausdrücklich eingeschaltet.
	'ignore_sticky_posts' => ! pfadi_mod( 'pfadi_hero_sticky', false ),
	'no_found_rows'       => true,
);
$excl = pfadi_excluded_cat_ids();
if ( $excl ) {
	$args['category__not_in'] = $excl;
}
$cat  = (int) pfadi_mod( 'pfadi_hero_cat', 0 );
if ( $cat ) {
	$args['cat'] = $cat;
}
$q = new WP_Query( $args );
if ( ! $q->have_posts() ) {
	return;
}
?>
<section class="hero-bento">
	<div class="container">
		<div class="bento">
			<?php
			$i = 0;
			while ( $q->have_posts() && $i < 5 ) :
				$q->the_post();
				$GLOBALS['pfadi_shown'][] = get_the_ID();
				$i++;
				?>
				<article class="bento__item bento__item--<?php echo (int) $i; ?>">
					<a class="bento__link" href="<?php the_permalink(); ?>">
						<span class="bento__media"><?php pfadi_thumbnail( 1 === $i ? 'pfadi-hero' : 'pfadi-card' ); ?></span>
						<span class="bento__body">
							<?php pfadi_category_pill( false, 1 === $i ? 3 : 1 ); ?>
							<?php if ( 1 === $i ) : ?>
								<h2 class="bento__title"><?php the_title(); ?></h2>
								<span class="bento__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></span>
							<?php else : ?>
								<h3 class="bento__title"><?php the_title(); ?></h3>
							<?php endif; ?>
							<span class="bento__date"><?php echo esc_html( get_the_date() ); ?></span>
						</span>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
</section>
<?php
wp_reset_postdata();
