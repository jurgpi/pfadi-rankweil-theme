<?php
/**
 * Beitragskarte.
 *
 * @package pfadi-rankweil
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>
	<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php pfadi_thumbnail( 'pfadi-card', '(max-width: 640px) calc(100vw - 32px), (max-width: 1024px) 50vw, 400px' ); ?>
	</a>
	<div class="card__body">
		<?php pfadi_category_pill(); ?>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php pfadi_post_meta(); ?>
	</div>
</article>
