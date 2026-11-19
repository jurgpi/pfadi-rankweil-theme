<?php
/**
 * Fallback-Vorlage / Beitragsliste / Archive / Suche.
 *
 * @package pfadi-rankweil
 */

get_header();

if ( is_search() ) {
	/* translators: %s: Suchbegriff */
	pfadi_page_hero( sprintf( __( 'Suche: %s', 'pfadi-rankweil' ), '<span>' . esc_html( get_search_query() ) . '</span>' ) );
} elseif ( is_category() ) {
	$pfadi_s = pfadi_stufe_of_term( get_queried_object() );
	pfadi_page_hero( single_cat_title( '', false ), get_the_archive_description(), $pfadi_s[0] ? 'is-stufe stufe-' . $pfadi_s[0] : '' );
} elseif ( is_archive() ) {
	pfadi_page_hero( get_the_archive_title(), get_the_archive_description() );
} else {
	$posts_page = get_option( 'page_for_posts' );
	pfadi_page_hero( $posts_page ? get_the_title( $posts_page ) : __( 'Berichte', 'pfadi-rankweil' ) );
}
?>
<div class="container layout">
	<div class="layout__main">
		<?php if ( have_posts() ) : ?>
			<div class="card-grid card-grid--archive">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/card' );
				endwhile;
				?>
			</div>
			<?php pfadi_pagination(); ?>
		<?php else : ?>
			<div class="empty-state">
				<h2><?php esc_html_e( 'Nichts gefunden', 'pfadi-rankweil' ); ?></h2>
				<p><?php esc_html_e( 'Probier es mit einem anderen Suchbegriff.', 'pfadi-rankweil' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php
get_footer();
