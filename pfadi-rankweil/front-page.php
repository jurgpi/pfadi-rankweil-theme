<?php
/**
 * Startseite.
 *
 * @package pfadi-rankweil
 */

// Blättern auf der Startseite (/page/2/) → normale Beitragsliste.
if ( 'posts' === get_option( 'show_on_front' ) && is_paged() ) {
	get_template_part( 'index' );
	return;
}

get_header();

$GLOBALS['pfadi_shown'] = array();

/**
 * Widgets der Seitenleiste (z. B. „Neues aus der Gruppe“, „Wichtiges“, Newsletter) als Karten
 * auf der Startseite – wie früher im Startseiten-Bereich von HitMag.
 */
$pfadi_side_pos = pfadi_mod( 'pfadi_front_sidebar', 'after_news' );
$pfadi_side     = function ( $where ) use ( $pfadi_side_pos ) {
	if ( $where !== $pfadi_side_pos || ! is_active_sidebar( 'sidebar-1' ) ) {
		return;
	}
	?>
	<section class="section section--tight home-sidebar">
		<div class="container home-widgets"><?php dynamic_sidebar( 'sidebar-1' ); ?></div>
	</section>
	<?php
};

if ( pfadi_mod( 'pfadi_hero_enable', true ) ) {
	get_template_part( 'template-parts/hero', pfadi_mod( 'pfadi_hero_style', 'bento' ) );
}

$pfadi_side( 'after_hero' );

if ( pfadi_mod( 'pfadi_stufen_enable', true ) ) {
	get_template_part( 'template-parts/stufen' );
}

$pfadi_side( 'after_stufen' );

// Statische Startseite: Seiteninhalt nur anzeigen, wenn im Customizer eingeschaltet.
// (HitMag hat den Text der Startseite mit der Vorlage „Magazine“ ebenfalls ignoriert –
// dort stehen oft noch alte Reste.)
if ( 'page' === get_option( 'show_on_front' ) && pfadi_mod( 'pfadi_front_content', false ) ) {
	while ( have_posts() ) :
		the_post();
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<section class="section">
				<div class="container container--narrow entry-content"><?php the_content(); ?></div>
			</section>
			<?php
		endif;
	endwhile;
}

get_template_part( 'template-parts/home-news' );

$pfadi_side( 'after_news' );

if ( is_active_sidebar( 'home-1' ) ) :
	?>
	<section class="section section--tight">
		<div class="container home-widgets"><?php dynamic_sidebar( 'home-1' ); ?></div>
	</section>
	<?php
endif;

if ( pfadi_mod( 'pfadi_cta_enable', true ) ) {
	get_template_part( 'template-parts/cta' );
}

get_footer();
