<?php
/**
 * Template Name: Seite mit Seitenleiste
 *
 * @package pfadi-rankweil
 */

get_header();

while ( have_posts() ) :
	the_post();
	pfadi_page_hero( get_the_title() );
	?>
	<div class="container layout">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'layout__main' ); ?>>
			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="page-thumb"><?php the_post_thumbnail( 'pfadi-hero' ); ?></figure>
			<?php endif; ?>
			<div class="entry-content"><?php the_content(); ?></div>
		</article>
		<?php get_sidebar(); ?>
	</div>
	<?php
endwhile;

get_footer();
