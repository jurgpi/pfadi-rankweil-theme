<?php
/**
 * 404.
 *
 * @package pfadi-rankweil
 */

get_header();
pfadi_page_hero( __( 'Verlaufen?', 'pfadi-rankweil' ), __( 'Diese Seite gibt es leider nicht. Kein Problem – auch beste Pfadfinder*innen brauchen mal einen Kompass.', 'pfadi-rankweil' ) );
?>
<div class="container container--narrow section empty-state">
	<?php get_search_form(); ?>
	<p><a class="btn btn--accent" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Zurück zur Startseite', 'pfadi-rankweil' ); ?></a></p>
</div>
<?php
get_footer();
