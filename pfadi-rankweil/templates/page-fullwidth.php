<?php
/**
 * Template Name: Volle Breite (ohne Titelbereich)
 *
 * Ideal für Seiten, die komplett mit Blöcken gestaltet werden.
 *
 * @package pfadi-rankweil
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'fullwidth' ); ?>>
		<div class="entry-content entry-content--full"><?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
